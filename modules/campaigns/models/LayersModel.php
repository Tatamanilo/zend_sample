<?php

class LayersModel
{
    /**
     * Получить прокладки кампании
     * @return mixed
     */
    public function getCampaignLayers($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_layer
            WHERE
                idCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCampaign));
        return $stmt->fetchAll();
    }


    /**
     * изменить статус прокладки
     * @param $idLayer
     * @param $changeTo новый статус
     */
    public function changeStatus($idLayer, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_layer
            SET
              layerStatus = ?
            WHERE
              idLayer = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idLayer));
    }

    /**
     * изменить тип приватности прокладки
     * @param $idLayer
     * @param $changeTo новый тип
     */
    public function changeForPrivate($idLayer, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_layer
            SET
              forPrivate = ?
            WHERE
              idLayer = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idLayer));
    }


    /**
     * Добавление прокладки
     * @param $data
     */
    public function addLayer($idCampaign, $data)
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
                smp_layer
            SET ' . implode(' , ', $set) . '
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }

    /**
     * Редактирование прокладки
     * @param $data
     */
    public function editLayer($idLayer, $data)
    {
        $db = Zend_Registry::get('db');

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }
        $setData["idLayer"] = $idLayer;

         $sql = '
            UPDATE
                smp_layer
            SET ' . implode(' , ', $set) . '
            WHERE
                idLayer = :idLayer
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }

    /**
     * Получить данные прокладки
     * @return mixed
     */
    public function getLayer($idLayer)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_layer
            WHERE
                idLayer = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idLayer));
        return $stmt->fetch();
    }


    /**
     * Добавление пользователя прокладки
     * @param $data
     */
    public function addUserToLayer($idLayer, $idUser)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            INSERT IGNORE INTO
                smp_layeruser
            SET
                idLayer = ?,
                idUser = ?
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idLayer, $idUser));
    }

    /**
     * Удаление пользователя прокладки
     * @param $data
     */
    public function deleteUserFromLayer($idLayer, $idUser)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_layeruser
            WHERE
                idLayer = ? AND
                idUser = ?
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idLayer, $idUser));
    }

    /**
     * Удаление пользователя прокладки
     * @param $data
     */
    public function deleteUsersFromLayer($idLayer, $idUsers)
    {
        $db = Zend_Registry::get('db');

         $sql = '
            DELETE FROM
                smp_layeruser
            WHERE
                idLayer = ? AND
                idUser IN ('.implode(",", $idUsers).')
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idLayer));
    }

    /**
     * Добавление пользователей прокладки
     * @param $data
     */
    public function addUsersToLayer($idLayer, $idUsers)
    {
        $db = Zend_Registry::get('db');

        foreach ($idUsers as $idUser)
        {
            $values[] = '('.$idLayer.', '.$idUser.')';
        }

        $sql = '
            INSERT IGNORE INTO
                smp_layeruser
                (idLayer, idUser)
            VALUES
                ' . implode(' , ', $values) . '
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute();
    }


    /**
     * Получить пользователей прокладки
     * @return mixed
     */
    public function getLayerUsers($idLayer)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                *
            FROM
                smp_layeruser as lu,
                smp_user as u
            WHERE
                u.idUser = lu.idUser AND
                lu.idLayer = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idLayer));
        return $stmt->fetchAll();
    }

    /**
     * временная статистика для лендинга
     * @return mixed
     */
    public function getLayerStat($idLayer, $period = false)
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
                idLayer = ?
                ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idLayer));
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
                idLayer = ?
            GROUP BY
                transStatus';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idLayer));
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