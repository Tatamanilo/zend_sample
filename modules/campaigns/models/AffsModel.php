<?php

class AffsModel
{


    /**
     * Получить пользователей кампании
     * @param $isIndividual если false - не учитывать этот параметр, 1 - индивидуальных, 0 - групповых
     * @return mixed
     */
    public function getCampaignUsers($idCampaign, $isIndividual = false)
    {
        $db = Zend_Registry::get('db');

        $data = array($idCampaign);
        $where = '';

        if ($isIndividual !== false)
        {
            $where = 'AND cg.isIndividual = ?';
            $data[] = $isIndividual;
        }
        $sql ='
            SELECT
                *
            FROM
                smp_campaignuser as cg,
                smp_user as u
            WHERE
                u.idUser = cg.idUser AND
                cg.idCampaign = ?
                '.$where.'
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchAll();
    }

    /**
     * Получить группы пользователей кампании
     * @return mixed
     */
    public function getCampaignUserGroups($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                *
            FROM
                smp_campaigngroup as cg,
                smp_usergroup as ug
            WHERE
                ug.idUserGroup = cg.idUserGroup AND
                cg.idCampaign = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCampaign));
        return $stmt->fetchAll();
    }


    /**
     * Получить айдишники груп пользователей кампании
     * @return mixed
     */
    public function getCampaignUserGroupsIds($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                idUserGroup
            FROM
                smp_campaigngroup
            WHERE
                idCampaign = ?
            ';
        return $db->fetchCol($sql, array($idCampaign));
    }


    /**
     * Добавление пользователя кампании
     * @param $data
     */
    public function addUserToCampaign($idCampaign, $idUser, $isIndividual = 1)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            INSERT IGNORE INTO
                smp_campaignuser
            SET
                idCampaign = ?,
                idUser = ?,
                isIndividual = ?
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCampaign, $idUser, $isIndividual));
    }


    /**
     * Добавление группы пользователей кампании
     * @param $data
     */
    public function addGroupToCampaign($idCampaign, $idUserGroup)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            INSERT IGNORE INTO
                smp_campaigngroup
            SET
                idCampaign = ?,
                idUserGroup = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCampaign, $idUserGroup));
    }


    /**
     * Добавление пользователей кампании
     * @param $data
     */
    public function addUsersToCampaign($idCampaign, $idUsers, $isIndividual = 0)
    {
        $db = Zend_Registry::get('db');

        if (empty($idUsers))
        {
            return false;
        }

        $values = array();
        foreach ($idUsers as $idUser)
        {
            $values[] = '("'.$idCampaign.'", '.$idUser.', '.$isIndividual.')';
        }


        $sql = '
            INSERT IGNORE INTO
                smp_campaignuser
                (idCampaign, idUser, isIndividual)
            VALUES
                ' . implode(' , ', $values) . '
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }


    /**
     * Удаление пользователя кампании
     * @param $data
     */
    public function deleteUserFromCampaign($idCampaign, $idUser, $isIndividual = 1)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_campaignuser
            WHERE
                idCampaign = ? AND
                idUser = ? AND
                isIndividual = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCampaign, $idUser, $isIndividual));
    }

    /**
     * Удаление пользователя кампании
     * @param $data
     */
    public function deleteUsersFromCampaign($idCampaign, $idUsers, $isIndividual = 1)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_campaignuser
            WHERE
                idCampaign = ? AND
                idUser IN ('.implode(",", $idUsers).') AND
                isIndividual = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCampaign, $isIndividual));
    }


    /**
     * Удаление пользователя кампании
     * @param $data
     */
    public function deleteGroupFromCampaign($idCampaign, $idUserGroup)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_campaigngroup
            WHERE
                idCampaign = ? AND
                idUserGroup = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCampaign, $idUserGroup));
    }


    /**
     * отобрать из списка пользователей только тех кто принадлежит приватной кампании
     * @param $data
     */
    public function filterPrivateCampaignUsers($idCampaign, $idUsers)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                idUser
            FROM
                smp_campaignuser
            WHERE
                idUser IN ('.implode(",", $idUsers).')  AND
                idCampaign = ?
            ';
        return $idUsersCampaign = $db->fetchCol($sql, array($idCampaign));
    }


    /**
     * отобрать из списка пользователей только тех кто принадлежит приватной кампании
     * @param $data
     */
    public function checkPrivateCampaignUser($idCampaign, $idUser)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                idUser
            FROM
                smp_campaignuser
            WHERE
                idUser = ?  AND
                idCampaign = ?
            ';
        $idUserCampaign = $db->fetchCol($sql, array($idUser, $idCampaign));

        if ($idUserCampaign)
        {
            return true;
        }
        return false;
    }


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
            WHERE
                userGroupStatus = "E"
            ORDER BY
                userGroupName ASC
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute();
        $groups = $stmt->fetchAll();

        if ($calcUsersCount)
        foreach ($groups as $key => $group)
        {
            $groups[$key]["count"] = count(explode(';', $group['idUsers']));
        }
        return $groups;
    }


    /**
     * Получить айдишники пользователей по массиву айдишников групп
     * @param $groupIds массив айдишников групп
     * @return mixed
     */
    public function getUserIdsByGroupIds($groupIds)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                idUsers
            FROM
                smp_usergroup
            WHERE
                idUserGroup IN (' . implode(',', $groupIds) . ')
            ';

        return $db->fetchCol($sql);
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
}