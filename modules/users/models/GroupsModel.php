<?php

class GroupsModel
{
    /**
     * Получить группы пользователей
     * @param $calcUsersCount выполнять ли подсчет количество пользователей в группе
     * @return mixed
     */
    public function getGroups($calcUsersCount = true)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_usergroup
            ORDER BY
                userGroupName ASC
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $groups = $stmt->fetchAll();

        if ($calcUsersCount)
        foreach ($groups as $key => $group)
        {
            $groups[$key]["count"] = !empty($group['idUsers']) ? count(explode(';', $group['idUsers'])) : 0;
        }
        return $groups;
    }

    /**
     * Получить пользователей группы по айдишникам
     * @param string $ids - список айди через запятую
     * @return mixed
     */
    public function getUsers($ids)
    {
        $db = Zend_Registry::get('db');

        if (!empty($ids))
        {
            $sql =
                'SELECT
                    *
                FROM
                    smp_user
                WHERE
                    idUser IN ('.$ids.')
                ';

            $stmt = $db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        }
        else
        {
            return false;
        }
    }

    /**
     * Получить данные группы
     * @param $id
     * @return mixed
     */
    public function getGroup($id)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_usergroup
            WHERE
                idUserGroup = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($id));
        return $stmt->fetch();
    }

    /**
     * Добавление данных группы
     * @param $data
     */
    public function addGroup($data)
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
                smp_usergroup
            SET ' . implode(' , ', $set) . '
            ';


        $stmt = $db->prepare($sql);
        $stmt->execute($setData);
        return $db->lastInsertId();
    }

    /**
     * редактирование данных группы
     * @param $id
     * @param $data
     */
    public function editGroup($id, $data)
    {
        $db = Zend_Registry::get('db');

        $set = array();
        $dataArr = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $dataArr[$key] = $field;
        }
        $dataArr["idUserGroup"] = $id;

         $sql = '
            UPDATE
                smp_usergroup
            SET
                ' . implode(' , ', $set) . '
            WHERE
                idUserGroup = :idUserGroup
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute($dataArr);
    }

    /**
     * удаление группы
     * @param $id
     */
    public function deleteGroup($id)
    {
        $db = Zend_Registry::get('db');

        $dataArr["idUserGroup"] = $id;

         $sql = '
            DELETE FROM
                smp_usergroup
            WHERE
                idUserGroup = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($id));
    }

    /**
     * Получить пользователей для списка с автодополнением
     * @param $idRole айди роли
     * @param $term введенная строка в поле в автодополнением
     * @return mixed
     */
    public function getFreeUsersList($idRole, $term)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                idUser as id,
                login as value,
                login,
                name,
                userRef
            FROM
                smp_user
            WHERE
                idRole = ? AND
                (login LIKE ? OR name LIKE ? OR userRef = ?) AND
                COALESCE(idUserGroup, 0) = 0
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idRole, $term."%", $term."%", $term));
        return $stmt->fetchAll();
    }

    /**
     * Установить группу для пользователей (исключать те которые входят в какуюто группу)
     * @param $usersToAdd массив айди пользователей
     * @param $idUserGroup айди группы
     * @return mixed
     */
    public function setGroupForUsers($usersToAdd, $idUserGroup)
    {
        $db = Zend_Registry::get('db');

        if (empty($usersToAdd))
        {
            return false;
        }
        $sql =
            'UPDATE
                smp_user
            SET
                idUserGroup = ?
            WHERE
                idUser IN ('. implode("," , $usersToAdd) .') AND
                COALESCE(idUserGroup, 0) = 0
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idUserGroup));
    }

    /**
     * деустановить группу для пользователей
     * @param $usersToDelete массив айди пользователей для деустановки их группы
     * @param $idUserGroup айди группы
     * @return mixed
     */
    public function unsetGroupForUsers($usersToDelete, $idUserGroup)
    {
        $db = Zend_Registry::get('db');

        if (empty($usersToDelete))
        {
            return false;
        }
        $sql =
            'UPDATE
                smp_user
            SET
                idUserGroup = NULL
            WHERE
                idUser IN ('. implode("," , $usersToDelete) .') AND
                idUserGroup = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idUserGroup));
    }


    /**
     * Получить пользователей для которых установленна группа конкретная
     * @param $idUserGroup айди группы
     * @return mixed
     */
    public function getGroupUsers($idUserGroup)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                idUser
            FROM
                smp_user
            WHERE
                idUserGroup = ?
            ';
        return $db->fetchCol($sql, array($idUserGroup));
    }

    /**
     * Получить кампании к которым привязана группа
     * @param $idUserGroup айди группы
     * @return mixed
     */
    public function getCampaignsAssignedToGroup($idUserGroup)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                idCampaign
            FROM
                smp_campaigngroup
            WHERE
                idUserGroup = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idUserGroup));
        return $stmt->fetchAll();
    }

    /**
     * Получить связки коммиссий к которым привязана группа
     * @param $idUserGroup айди группы
     * @return mixed
     */
    public function getCommissionSectionsAssignedToGroup($idUserGroup)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                csg.idCommissionSection,
                cs.idCampaign
            FROM
                smp_commissionsectiongroup as csg,
                smp_commissionsection as cs
            WHERE
                cs.idCommissionSection = csg.idCommissionSection AND
                idUserGroup = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idUserGroup));
        return $stmt->fetchAll();
    }

    /**
     * изменить статус группы
     * @param $idUserGroup
     * @param $changeTo новый статус
     */
    public function changeStatus($idUserGroup, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_usergroup
            SET
              userGroupStatus = ?
            WHERE
              idUserGroup = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idUserGroup));
    }
}