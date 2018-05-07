<?php

class ReclsModel
{
    /**
     * Достать пользователей по идентификаторм ролей
     * @param $idRole
     * @param $pager обьект пагинации
     * @param $fields список полей для получения в запросе
     * @param $form данные формы фильтра
     * @return mixed
     */
    public function getRecls(&$pager, $form = false)
    {
        $db = Zend_Registry::get('db');

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
                    case "reclStatus":
                        $available_statuses = array('E', 'D');
                        // проверить являются ли значения стутсов одним из допустимых
                        if (count(array_unique(array_merge($available_statuses, $filter_value))) == count($available_statuses))
                        {
                            // добавить условие если выбраны не все статусы
                            if (count($filter_value) < count($available_statuses))
                            {
                                $where[] =  'reclStatus IN ("' . implode('","', $filter_value).'")';
                            }
                        }
                        break;
                    case "idRecl":
                        $where[] =  $field. ' = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    default:
                        $where[] =  $field. ' LIKE :' . $field;
                        $data[$field] = $filter_value."%";
                        break;
                }
            }
        }
        $pager['total'] = $this->_getReclsCount($where, $data);

        $sql =
            'SELECT
                *
            FROM
                smp_recl
            WHERE
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
    private function _getReclsCount($where, $data)
    {
        $db = Zend_Registry::get('db');

        //echo
        $sql ='
            SELECT
                count(*)
            FROM
                smp_recl AS u
            WHERE
                (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
            ';
        return $db->fetchOne($sql, $data);
    }

    /**
     * Получить реклов для списка с автодополнением
     * @return mixed
     */
    public function getReclsList($str, $activeOnly)
    {
        $db = Zend_Registry::get('db');

        $where = '';
        if ($activeOnly)
        {
            $where = 'reclStatus = "E" AND';
        }

        $sql =
            'SELECT
                idRecl as id,
                reclName as value,
                reclName as label,
                reclStatus
            FROM
                smp_recl
            WHERE
                '.$where.' 
                reclName LIKE ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($str."%"));
        return $stmt->fetchAll();
    }


    /**
     * изменить статус оффера
     * @param $idCampaign
     * @param $changeTo новый статус
     */
    public function changeStatus($idRecl, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_recl
            SET
              reclStatus = ?
            WHERE
              idRecl = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idRecl));
    }

    /**
     * Добавление рекла
     * @param $data
     */
    public function addRecl($data)
    {
        $db = Zend_Registry::get('db');

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }

         $sql = '
            INSERT INTO
                smp_recl
            SET ' . implode(' , ', $set) . '
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute($setData);
        return $db->lastInsertId();
    }


    /**
     * Редактирование рекла
     * @param $idRecl
     * @param $data
     */
    public function editRecl($idRecl, $data)
    {
        $db = Zend_Registry::get('db');

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }
        $setData["idRecl"] = $idRecl;

         $sql = '
            UPDATE
                smp_recl
            SET ' . implode(' , ', $set) . '
            WHERE
                idRecl = :idRecl
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }

    /**
     * Получить данные рекла
     * @return mixed
     */
    public function getRecl($idRecl)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_recl
            WHERE
                idRecl = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idRecl));
        return $stmt->fetch();
    }
}