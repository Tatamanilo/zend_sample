<?php

class UsersModel
{
    /**
     * Достать пользователей по идентификаторм ролей
     * @param $idRole
     * @param $pager обьект пагинации
     * @param $fields список полей для получения в запросе
     * @param $form данные формы фильтра
     * @return mixed
     */
    public function getUsersByRole($idRole, &$pager, $fields, $form = false)
    {
        $db = Zend_Registry::get('db');



        $select = $this->_prepareSelect($fields);

        $where = array();
        $data = array();
        if (!empty($form))
        foreach ($form as $filter_key=>$filter_value)
        {

            if (!empty($filter_value))
            {
                $field = str_replace('search_', '', $filter_key);
                switch ($field)
                {
                    case "dateperiod":
                        $available_periods = array("1 DAY", "3 DAY", "1 WEEK", "all", "custom");

                        // проверить на допустимые значения
                        if (!in_array($filter_value, $available_periods))
                        {
                            break;
                        }

                        if ($filter_value == "all" || $filter_value == "custom")
                        {
                            break;
                        }
                        else
                        {
                            $where[] =  'registrationDate  >= DATE_SUB(now(), INTERVAL '.$filter_value.')';
                        }
                        break;
                    case "datefrom":
                        $where[] =  'registrationDate >= :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "dateto":
                        $where[] =  'registrationDate  <= DATE_ADD(:'.$field.', INTERVAL 1 DAY)';
                        $data[$field] = $filter_value;
                        break;
                    case "status":
                        $available_statuses = array('active', 'wait', 'ban', 'delete');
                        // проверить являются ли значения стутсов одним из допустимых
                        if (count(array_unique(array_merge($available_statuses, $filter_value))) == 4)
                        {
                            // добавить условие если выбраны не все статусы
                            if (count($filter_value) < count($available_statuses))
                            {
                                $where[] =  'status IN ("' . implode('","', $filter_value).'")';
                            }
                        }
                        break;
                    case "checked":
                        $available_checked = array(0, 1);
                        if (count(array_unique(array_merge($available_checked, $filter_value))) == 2)
                        {
                            if (count($filter_value) < count($available_checked))
                            {
                                $where[] =  'checked IN (' . implode(",", $filter_value).')';
                            }
                        }
                        break;
                    case "freeze":
                        $available_freeze = array(0, 1);
                        if (count(array_unique(array_merge($available_freeze, $filter_value))) == 2)
                        {
                            if (count($filter_value) < count($available_freeze))
                            {
                                $where[] =  'freeze IN (' . implode(",", $filter_value).')';
                            }
                        }
                        break;
                    default:
                        $where[] =  $field. ' LIKE :' . $field;
                        $data[$field] = $filter_value."%";
                        break;
                }
            }


        }
        $data["idRole"] = $idRole;

        $pager['total'] = $this->_getUsersCount($where, $data);

        $sql =
            'SELECT
                *
            FROM
                smp_user
            WHERE
                idRole = :idRole AND
                (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
            ' .
            $pager['orderSQL'] .
            $pager['limitSQL'];

        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchAll();
    }

    /**
     * Достает количество Пользователей по их idRole
     * @param $where - массив условий
     * @param $data - массив значений условий
     */
    private function _getUsersCount($where, $data)
    {
        $db = Zend_Registry::get('db');

        //echo
        $sql ='
            SELECT
                count(*)
            FROM
                smp_user AS u
            WHERE
                idRole = :idRole AND
                (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
            ';
        return $db->fetchOne($sql, $data);
    }

    /**
     * Достает количество вхождений пользователя в систему
     * @param $idUser
     * @return mixed
     */
    private function _loginHistoryCount($idUser)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                count(*)
            FROM
                smp_useractivity AS u
            WHERE
                idUser = ?
            ';
        return $db->fetchOne($sql, array($idUser));
    }


    /**
     * Добавление данных пользователя
     * @param $data
     */
    public function addUser($data)
    {
        $fields = array('login' => 'login', 'pass' => 'pass', 'idRecl' => 'idRecl',
            'role' => 'idRole', 'status' => 'status',
            'idUser' => 'idUser', 'name' => 'name',
            'email' => 'email', 'additionalInfo' => 'additionalInfo');

        $db = Zend_Registry::get('db');

        $set = array();
        $setData = array();
        foreach ($fields as $key => $field)
        {
            if (isset($data[$key]) && !empty($data[$key]))
            {
                $set[] = $field . ' = :' . $field;
                if ($key == 'pass')
                {
                    $data[$key] = md5($data[$key]);
                }
                $setData[$field] = $data[$key];
            }
        }

        $sql = '
            INSERT INTO
                smp_user
            SET ' . implode(' , ', $set) . '
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute($setData);
        return $db->lastInsertId();
    }

    /**
     * Редактирование основной информации пользователя
     * @param $data
     * @param $idUser
     * @return mixed
     */
    public function editUser($data, $idUser)
    {
        $fields = array('login' => 'login', 'pass' => 'pass',
            'role' => 'idRole', 'status' => 'status',
            'holdDisabled' => 'holdDisabled', 'freeze' => 'freeze', 'checked' => 'checked',
            'name' => 'name', 'email' => 'email', 'additionalInfo' => 'additionalInfo', 'wmr' => 'wmr', 'wmid' => 'wmid');
        $db = Zend_Registry::get('db');

        $set = array();
        $setData = array();
        foreach ($fields as $key => $field)
        {
            if (isset($data[$key]) && !empty($data[$key]))
            {
                $set[] = $field . ' = :' . $field;
                if ($key == 'pass')
                {
                    $data[$key] = md5($data[$key]);
                }
                $setData[$field] = $data[$key];
            }
        }

        $setData['idUser'] = $idUser;

        $sql = '
            UPDATE
                smp_user
            SET
                ' . implode(' , ', $set) . '
            WHERE
                idUser = :idUser
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute($setData);
    }

    /**
     * Достает пользователя по его идентификатору
     * @param $idUser
     * @return mixed
     */
    public function getUserById($idUser)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_user
            WHERE
              idUser = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idUser));
        return $stmt->fetch();
    }

    /**
     * Достает историю логинов пользователя по его идентификатору
     * @param $idUser
     * @param $pager
     * @return mixed
     */
    public function loginHistory($idUser, &$pager)
    {
        $db = Zend_Registry::get('db');

        $pager['total'] = $this->_loginHistoryCount($idUser);

        $sql =
            'SELECT
                *
            FROM
                smp_useractivity
            WHERE
                idUser = ?'.
            $pager['orderSQL'] .
            $pager['limitSQL'];

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idUser));
        return $stmt->fetchAll();
    }

    /**
     * Отметить пользователя, как удаленного
     * @param $idUser
     */
    public function setDeleteStatus($idUser)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_user
            SET
              status = \'delete\'
            WHERE
              idUser = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idUser));
    }

    /**
     * Изменить статус пользователя
     * @param $idUser
     * @param $changeTo новый статус пользователя
     */
    public function changeStatus($idUser, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_user
            SET
              status = ?
            WHERE
              idUser = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idUser));
    }

    /**
     * Изменить типа пользователя проверен\непроверен
     * @param $idUser
     * @param $changeTo новый тип пользователя
     */
    public function changeChecked($idUser, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_user
            SET
              checked = ?
            WHERE
              idUser = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idUser));
    }


    /**
     * изменить статус выплат замороден\незаморожен
     * @param $idUser
     * @param $changeTo новый статус заморожен\не замрожен
     */
    public function changeFreeze($idUser, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_user
            SET
              freeze = ?
            WHERE
              idUser = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idUser));
    }


    /**
     * Получить пользователей для списка с автодополнением
     * @param $idRole айди роли
     * @param $term введенная строка в поле в автодополнением
     * @return mixed
     */
    public function getUsersList($idRole, $term, $activeOnly = true)
    {
        $db = Zend_Registry::get('db');

        $where = '';
        if ($activeOnly)
        {
            $where = 'status = "active" AND';
        }
        $sql =
            'SELECT
                idUser as id,
                login as value,
                status,
                login,
                name,
                userRef
            FROM
                smp_user
            WHERE
                '.$where.'
                idRole = ? AND
                (login LIKE ? OR name LIKE ? OR userRef = ?)
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idRole, $term."%", $term."%", $term));
        return $stmt->fetchAll();
    }

    /**
     * Подготавливает Набор полей, для select
     * @param $fields
     * @return array
     */
    private function _prepareSelect($fields)
    {
        $select = array('*');
        if (!empty($fields))
        {
            $select = array();
            foreach ($fields as $key => $field)
            {
                if (!empty($field))
                {
                    $select[] = $key . ' AS ' . $field;
                }
                else
                {
                    $select[] = '\'\' AS empty_' . $key; //Для пустых строк, там где на фронте будут ссылки на обработку строк
                }
            }
        }
        return $select;
    }
}