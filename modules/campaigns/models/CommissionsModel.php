<?php

class CommissionsModel
{
    /**
     * Получить гео инфо кампании
     * @return mixed
     */
    public function getCampaignCommissionsGeo($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_commission as c
            LEFT JOIN
                smp_commissionprice as cp
            ON
                c.idCommission = cp.idCommission AND
                cp.validFrom <= now() AND cp.validTo >= now()
            WHERE
                idCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCampaign));
        return $stmt->fetchAll();
    }

    /**
     * Получить комиссии кампании
     * @return mixed
     */
    public function getCampaignCommissions($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                c.*,
                cp.*,
                cs.idCommissionSection,
                cs.commissionSectionName,
                cs.isGroupCommissionSection,
                c.idCommission as idCommission,
                r.reclName
            FROM

                smp_commissionsection as cs
            LEFT JOIN
                smp_commission as c
            ON
                cs.idCommissionSection = c.idCommissionSection
            LEFT JOIN
                smp_recl as r
            ON
                c.idRecl = r.idRecl
            LEFT JOIN
                smp_commissionprice as cp
            ON
                c.idCommission = cp.idCommission AND
                cp.validFrom <= now() AND cp.validTo >= now()
            WHERE
                cs.idCampaign = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCampaign));
        return $stmt->fetchAll();
    }

    /**
     * Получить реклов кампании
     * @return mixed
     */
    public function getCampaignRecls($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                rc.*,
                r.reclStatus,
                r.reclName
            FROM
                smp_reclcampaign as rc,
                smp_recl as r
            WHERE
                rc.idRecl = r.idRecl AND
                idcampaign = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCampaign));
        return $stmt->fetchAll();
    }


    /**
     * Получить коммиссии связки
     * @return mixed
     */
    public function getSectionCommissions($idCommissionSection)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_commission
            WHERE
                idCommissionSection = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommissionSection));
        return $stmt->fetchAll();
    }

    /**
     * Получить данные коммиссии
     * @return mixed
     */
    public function getCommission($idCommission)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_commission
            WHERE
                idCommission = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommission));
        return $stmt->fetch();
    }

    /**
     * Получить данные связки коммиссии
     * @return mixed
     */
    public function getCommissionSection($idCommissionSection)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_commissionsection
            WHERE
                idCommissionSection = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommissionSection));
        return $stmt->fetch();
    }

    /**
     * Получить пользователей коммиссии
     * @return mixed
     */
    public function getCommissionUsers($idCommission)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                *
            FROM
                smp_commissionuser as cg,
                smp_user as u
            WHERE
                u.idUser = cg.idUser AND
                cg.idCommission = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommission));
        return $stmt->fetchAll();
    }

    /**
     * Получить пользователей связки коммиссии
     * @param $isIndividual если false - не учитывать этот параметр, 1 - индивидуальных, 0 - групповых
     * @return mixed
     */
    public function getCommissionSectionUsers($idCommissionSection, $isIndividual = false)
    {
        $db = Zend_Registry::get('db');

        $data = array($idCommissionSection);
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
                smp_commissionsectionuser as cg,
                smp_user as u
            WHERE
                u.idUser = cg.idUser AND
                cg.idCommissionSection = ?
                '.$where.'
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchAll();
    }

    /**
     * Получить айди пользователей коммиссии
     * @return mixed
     */
    public function getCommissionUsersIds($idCommission)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                idUser
            FROM
                smp_commissionuser
            WHERE
                idCommission = ?
            ';

        return $db->fetchCol($sql, array($idCommission));
    }


    /**
     * Получить группы пользователей связки
     * @return mixed
     */
    public function getCommissionSectionUserGroups($idCommissionSection)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                *
            FROM
                smp_commissionsectiongroup as cg,
                smp_usergroup as ug
            WHERE
                ug.idUserGroup = cg.idUserGroup AND
                cg.idCommissionSection = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommissionSection));
        return $stmt->fetchAll();
    }


    /**
     * изменить статус коммиссии
     * @param $idCommission
     * @param $changeTo новый статус
     */
    public function changeStatus($idCommission, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_commission
            SET
              commissionStatus = ?
            WHERE
              idCommission = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idCommission));
    }

    /**
     * изменить тип подтверждения коммиссии (ручной, авто)
     * @param $idCommission
     * @param $changeTo новый статус
     */
    public function changeApproveType($idCommission, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_commission
            SET
              approveType = ?
            WHERE
              idCommission = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idCommission));
    }

    /**
     * Добавление коммиссии
     * @param $data
     */
    public function addCommission($idCampaign, $data)
    {
        $db = Zend_Registry::get('db');

        $data["idCampaign"] = $idCampaign;

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }

         $sql = '
            INSERT INTO
                smp_commission
            SET ' . implode(' , ', $set) . '
            ';


        $stmt = $db->prepare($sql);
        $stmt->execute($setData);
        return $db->lastInsertId();
    }

    /**
     * Добавление пользователя коммиссии
     * @param $data
     */
    public function addUserToCommission($idCommission, $idUser)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            INSERT IGNORE INTO
                smp_commissionuser
            SET
                idCommission = ?,
                idUser = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCommission, $idUser));
    }

    /**
     * Добавление пользователя коммиссии
     * @param $data
     */
    public function addUserToCommissionSection($idCommissionSection, $idCampaign, $idUser, $isIndividual = 1)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            INSERT IGNORE INTO
                smp_commissionsectionuser
            SET
                idCommissionSection = ?,
                idCampaign = ?,
                idUser = ?,
                isIndividual = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommissionSection, $idCampaign, $idUser, $isIndividual));
        return $db->lastInsertId();
    }


    /**
     * Добавление пользователей коммиссии
     * @param $data
     */
    public function addUsersToCommission($idCommission, $idUsers, $isIndividual = 0)
    {
        $db = Zend_Registry::get('db');

        foreach ($idUsers as $idUser)
        {
            $values[] = '('.$idCommission.', '.$idUser.', '.$isIndividual.')';
        }

        $sql = '
            INSERT IGNORE INTO
                smp_commissionuser
                (idCommission, idUser, isIndividual)
            VALUES
                ' . implode(' , ', $values) . '
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }


    /**
     * Добавление пользователей коммиссии
     * @param $data
     */
    public function addUsersToCommissionSection($idCommissionSection, $idCampaign, $idUsers, $isIndividual = 0)
    {
        $db = Zend_Registry::get('db');

        foreach ($idUsers as $idUser)
        {
            $values[] = '('.$idCommissionSection.', "'.$idCampaign.'", '.$idUser.', '.$isIndividual.')';
        }

        $sql = '
            INSERT IGNORE INTO
                smp_commissionsectionuser
                (idCommissionSection, idCampaign, idUser, isIndividual)
            VALUES
                ' . implode(' , ', $values) . '
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }


    /**
     * Добавление группы пользователей к связке
     * @param $data
     */
    public function addGroupToCommissionSection($idCommissionSection, $idCampaign, $idUserGroup)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            INSERT IGNORE INTO
                smp_commissionsectiongroup
            SET
                idCommissionSection = ?,
                idCampaign = ?,
                idUserGroup = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommissionSection, $idCampaign, $idUserGroup));
        return $db->lastInsertId();
    }

    /**
     * Удаление пользователя коммиссии
     * @param $data
     */
    public function deleteUserFromCommission($idCommission, $idUser)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_commissionuser
            WHERE
                idCommission = ? AND
                idUser = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCommission, $idUser));
    }

    /**
     * Удаление пользователя связки
     * @param $data
     */
    public function deleteUserFromCommissionSection($idCommissionSection, $idUser, $isIndividual = 1)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_commissionsectionuser
            WHERE
                idCommissionSection = ? AND
                idUser = ?  AND
                isIndividual = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCommissionSection, $idUser, $isIndividual));
    }

    /**
     * Удаление пользователей коммиссии
     * @param $data
     */
    public function deleteUsersFromCommission($idCommission, $idUsers)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_commissionuser
            WHERE
                idCommission = ? AND
                idUser IN ('.implode(",", $idUsers).')
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCommission));
    }

    /**
     * Удаление пользователей связки коммиссии
     * @param $data
     */
    public function deleteUsersFromCommissionSection($idCommissionSection, $idUsers, $isIndividual = 1)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_commissionsectionuser
            WHERE
                idCommissionSection = ? AND
                idUser IN ('.implode(",", $idUsers).') AND
                isIndividual = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCommissionSection, $isIndividual));
    }

    /**
     * Редактирование коммиссии
     * @param $data
     */
    public function editCommission($idCommission, $data)
    {
        $db = Zend_Registry::get('db');

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }
        $setData["idCommission"] = $idCommission;

         $sql = '
            UPDATE
                smp_commission
            SET ' . implode(' , ', $set) . '
            WHERE
                idCommission = :idCommission
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }

    /**
     * Получить цены коммиссии
     * @return mixed
     */
    public function getCommissionPrices($idCommission)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                *
            FROM
                smp_commissionprice
            WHERE
                idCommission = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommission));
        return $stmt->fetchAll();
    }

    /**
     * Получить список целей
     * @return mixed
     */
    public function getTargets()
    {
        return array('sale', 'appsale', 'validsale', 'paydsale', 'reg', 'level', 'effectreg', 'activeplayer');
    }

    /**
     * Добавление цены коммиссии
     * @param $idCommission
     * @param $data
     */
    public function addCommissionPrice($idCommission, $data)
    {
        $db = Zend_Registry::get('db');

        $data["idCommission"] = $idCommission;

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }

         $sql = '
            INSERT INTO
                smp_commissionprice
            SET ' . implode(' , ', $set) . '
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }

    /**
     * Редактирование цены коммиссии
     * @param $idCommissionPrice
     * @param $data
     */
    public function editCommissionPrice($idCommissionPrice, $data)
    {
        $db = Zend_Registry::get('db');

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }
        $setData["idCommissionPrice"] = $idCommissionPrice;

         $sql = '
            UPDATE
                smp_commissionprice
            SET ' . implode(' , ', $set) . '
            WHERE
                idCommissionPrice = :idCommissionPrice
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }


    /**
     * Получить страны
     * @return mixed
     */
    public function getCountries()
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_country
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }


    /**
     * Добавление связки коммиссии
     * @param $data
     */
    public function addCommissionSection($idCampaign, $data = false)
    {
        $db = Zend_Registry::get('db');

        if (empty($data))
        {
            $data["commissionSectionName"] = 'Связка для общих коммиссий';
            $data["isGroupCommissionSection"] = 0;
        }

        $data["idCampaign"] = $idCampaign;

        $set = array();
        $setData = array();

        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }

         $sql = '
            INSERT INTO
                smp_commissionsection
            SET ' . implode(' , ', $set) . '
            ';


        $stmt = $db->prepare($sql);
        $stmt->execute($setData);
        return $db->lastInsertId();
    }


    /**
     * Клонирование связки коммиссий
     * @param $data
     */
    public function cloneSection($idCommissionSection, $commissionSectionName = 'Приватная связка 1')
    {
        $data = array();
        $data["commissionSectionName"] = $commissionSectionName;
        $data["isGroupCommissionSection"] = 1;

        $commissionSection = $this->getCommissionSection($idCommissionSection);
        $idCommissionSectionNew = $this->addCommissionSection($commissionSection["idCampaign"], $data);
        $this->cloneSectionCommissions($idCommissionSection, $idCommissionSectionNew);
        return $idCommissionSectionNew;
    }

    /**
     * Клонирование коммиссий связки коммиссии
     * @param $data
     */
    public function cloneSectionCommissions($idCommissionSection, $idCommissionSectionNew)
    {
        $db = Zend_Registry::get('db');

        $sectionCommissions = $this->getSectionCommissions($idCommissionSection);

        foreach ($sectionCommissions as $sectionCommission)
        {
            $idCommission = $sectionCommission["idCommission"];
            unset($sectionCommission["idCommission"]);
            $sectionCommission["idCommissionSection"] = $idCommissionSectionNew;
            $sectionCommission["commissionName"] = $sectionCommission["commissionName"] . ' Групповая (клон)';
            $sectionCommission["isGroupCommission"] = 1;
            $idCommissionNew = $this->addCommission($sectionCommission["idCampaign"], $sectionCommission);
            $this->cloneCommissionPrices($idCommission, $idCommissionNew);
        }
    }


    /**
     * Добавление связки коммиссии
     * @param $data
     */
    public function cloneCommissionPrices($idCommission, $idCommissionNew)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            INSERT INTO
                smp_commissionprice
                (idCommission, priceRecl, priceAdvert, validFrom, validTo, descr)
            SELECT
                '.$idCommissionNew.', priceRecl, priceAdvert, validFrom, validTo, descr
            FROM
                smp_commissionprice
            WHERE
                idCommission = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCommission));
    }


    /**
     * изменить название связки
     * @param $idCommissionSection
     * @param $value новое название
     */
    public function editSectionName($idCommissionSection, $value)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
                smp_commissionsection
            SET
                commissionSectionName = ?
            WHERE
                idCommissionSection = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($value, $idCommissionSection));
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
     * Синхронизация пользователей связки в коммиссии (Переписать пользователей связки во все коммисси связки)
     * @param $idCommissionSection
     * @return mixed
     */
    public function syncSectionUsersToCommissions($idCommissionSection)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'DELETE FROM
                smp_commissionuser
            WHERE
                idCommission IN (SELECT smp_commission.idCommission FROM smp_commission WHERE smp_commission.idCommissionSection = ?)
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommissionSection));


        $sql =
            'INSERT INTO
                smp_commissionuser
                (idCommission, idUser, isIndividual)
            SELECT
                idCommission, idUser, isIndividual
            FROM
                smp_commissionsectionuser as csu
            INNER JOIN
                smp_commission as c
            ON
                c.idCommissionSection = csu.idCommissionSection
            WHERE
                c.idCommissionSection = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCommissionSection));
    }
}