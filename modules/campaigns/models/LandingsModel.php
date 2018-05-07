<?php

class LandingsModel
{
    /**
     * Получить лендинги кампании
     * @return mixed
     */
    public function getCampaignLandings($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_landing
            WHERE
                idCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCampaign));
        return $stmt->fetchAll();
    }


    /**
     * изменить статус лендинга
     * @param $idLanding
     * @param $changeTo новый статус
     */
    public function changeStatus($idLanding, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_landing
            SET
              landingStatus = ?
            WHERE
              idLanding = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idLanding));
    }

    /**
     * изменить тип приватности лендинга
     * @param $idLanding
     * @param $changeTo новый тип
     */
    public function changeForPrivate($idLanding, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_landing
            SET
              forPrivate = ?
            WHERE
              idLanding = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idLanding));
    }


    /**
     * Добавление лендинга
     * @param $data
     */
    public function addLanding($idCampaign, $data)
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
                smp_landing
            SET ' . implode(' , ', $set) . '
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }

    /**
     * Редактирование лендинга
     * @param $data
     */
    public function editLanding($idLanding, $data)
    {
        $db = Zend_Registry::get('db');

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }
        $setData["idLanding"] = $idLanding;

         $sql = '
            UPDATE
                smp_landing
            SET ' . implode(' , ', $set) . '
            WHERE
                idLanding = :idLanding
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }

    /**
     * Получить данные лендинга
     * @return mixed
     */
    public function getLanding($idLanding)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_landing
            WHERE
                idLanding = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idLanding));
        return $stmt->fetch();
    }


    /**
     * Добавление пользователя лендинга
     * @param $data
     */
    public function addUserToLanding($idLanding, $idUser)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            INSERT IGNORE INTO
                smp_landinguser
            SET
                idLanding = ?,
                idUser = ?
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idLanding, $idUser));
    }

    /**
     * Удаление пользователя лендинга
     * @param $data
     */
    public function deleteUserFromLanding($idLanding, $idUser)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_landinguser
            WHERE
                idLanding = ? AND
                idUser = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idLanding, $idUser));
    }

    /**
     * Удаление пользователя лендинга
     * @param $data
     */
    public function deleteUsersFromLanding($idLanding, $idUsers)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_landinguser
            WHERE
                idLanding = ? AND
                idUser IN ('.implode(",", $idUsers).')
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idLanding));
    }

    /**
     * Добавление пользователей лендинга
     * @param $data
     */
    public function addUsersToLanding($idLanding, $idUsers)
    {
        $db = Zend_Registry::get('db');

        foreach ($idUsers as $idUser)
        {
            $values[] = '('.$idLanding.', '.$idUser.')';
        }

        $sql = '
            INSERT IGNORE INTO
                smp_landinguser
                (idLanding, idUser)
            VALUES
                ' . implode(' , ', $values) . '
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }


    /**
     * Получить пользователей лендинга
     * @return mixed
     */
    public function getLandingUsers($idLanding)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                *
            FROM
                smp_landinguser as lu,
                smp_user as u
            WHERE
                u.idUser = lu.idUser AND
                lu.idLanding = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idLanding));
        return $stmt->fetchAll();
    }

    /**
     * временная статистика для лендинга
     * @return mixed
     */
    public function getLandingStat($idLanding, $period = false)
    {
        $db = Zend_Registry::get('db');

        $where = '';

        switch ($period)
        {
            case "today":
                $where = '
                groupTime = CURDATE() AND';
                break;
            case "yesterday":
                $where = '
                groupTime = (CURDATE()-INTERVAL 1 DAY) AND';
                break;
            case "week":
                $where = '
                groupTime BETWEEN CURDATE()-INTERVAL 1 WEEK AND CURDATE() AND';
                break;
        }


        $sql =
            '
            SELECT
                SUM(rawCount) as clicksCount,
                SUM(uniqCount) as uniqClicksCount
            FROM
                smp_clickstat
            WHERE
                '.$where.'
                groupType = "D" AND
                idLanding = ?
                ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idLanding));
        $clicksData = $stmt->fetch();

        $sql =
            '
            SELECT
                SUM(commissionSum) as commissionSum,
                SUM(transCount) as transCount,
                transStatus
            FROM
                smp_transactionstat
            WHERE
                '.$where.'
                idLanding = ?
            GROUP BY
                transStatus';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idLanding));
        $transactionsData = $stmt->fetchAll();

        $statsData = array();
        foreach ($transactionsData as $item)
        {
            $statsData["commissionSum" + $item["transStatus"]] = $item["commissionSum"];
            $statsData["transCount" + $item["transStatus"]] = $item["transCount"];

            // проссумировать коммиссии и количество транзакций чтобы получить общие значения, независимые от статуса, т.е ВСЕГО
            $statsData["commissionSumAll"] = isset($statsData["commissionSumAll"]) ? $statsData["commissionSumAll"] : 0 + $item["commissionSum"];
            $statsData["transCountAll"] = isset($statsData["transCount"]) ? $statsData["transCount"] : 0 + $item["transCount"];
        }

        $statsData["clicksCount"] = $clicksData["clicksCount"];
        $statsData["uniqClicksCount"] = $clicksData["uniqClicksCount"];

        if (!empty($statsData["uniqClicksCount"]))
        {
            $statsData["epc"] = $statsData["commissionSumA"] / $statsData["uniqClicksCount"];
        }
        else
        {
            $statsData["epc"] = 0;
        }

        if (!empty($statsData["transCountA"]))
        {
            $statsData["cr"] = $statsData["clicksCount"] / $statsData["transCountA"];   // ? A
        }
        else
        {
            $statsData["cr"] = 0;
        }

        if (!empty($statsData["clicksCount"]))
        {
            $statsData["crP"] = round($statsData["transCountA"] / $statsData["clicksCount"] * 100);
        }
        else
        {
            $statsData["crP"] = 0;
        }

        if (!empty($item["transCountAll"]))
        {
            $statsData["approvedPerc"] = $statsData["transCountA"] / $statsData["transCountAll"];
            $statsData["approvedWithPendPerc"] = ($statsData["transCountA"] + $statsData["transCountP"]) / $statsData["transCountAll"];
        }
        else
        {
            $statsData["approvedPerc"] = 0;
            $statsData["approvedWithPendPerc"] = 0;
        }
        return $statsData;
    }
}