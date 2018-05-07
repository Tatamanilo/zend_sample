<?php

class StatsModel
{

    /**
     * Получить кампании рекла
     * @return mixed
     */
    public function getReclCampaigns($idRecl)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                c.*
            FROM
                smp_reclcampaign as rc,
                smp_campaign as c
            WHERE
                rc.idCampaign = c.idCampaign AND
                rc.idRecl = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idRecl));
        return $stmt->fetchAll();
    }


    /**
     * Получить статистику учитывая фильтр и параметр группировки (день, неделя, месяц)
     * @param $form данные формы фильтра
     * @return mixed
     */
    public function getStats(&$pager, $form = false)
    {
        $db = Zend_Registry::get('db');

        $where = array();
        $data = array();
        if (!empty($form))
        foreach ($form as $filter_key => $filter_value)
        {
            if (!empty($filter_value))
            {
                $field = str_replace('search_', '', $filter_key);
                switch ($field)
                {
                    case "dateperiod":
                        $available_periods = array("1 WEEK", "1 MONTH", "all", "custom");

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
                            $where[] =  'groupTime BETWEEN CURDATE() - INTERVAL '.$filter_value.' AND CURDATE() ';
                        }
                        break;
                    case "datefrom":
                        $where[] =  'groupTime >= :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "dateto":
                        $where[] =  'groupTime  <= DATE_ADD(:'.$field.', INTERVAL 1 DAY)';
                        $data[$field] = $filter_value;
                        break;
                    case "idAff":
                        $where[] =  'idUser = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "idRecl":
                        $where[] =  'idRecl = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "idCampaign":
                        $where[] =  'idCampaign = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "idLanding":
                        $where[] =  'idLanding = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                }
            }
        }
        switch ($form["search_groupby"])
        {
            case "weeks":
                $groupByCol = 'CONCAT(DATE(groupTime - INTERVAL WEEKDAY(groupTime) DAY), " - ", DATE(groupTime - INTERVAL (WEEKDAY(groupTime) - 6) DAY))';
                $groupby = 'DATE_FORMAT(groupTime, "%u")';
                break;
            case "monthes":
                $groupByCol = 'DATE_FORMAT(groupTime, "%M %Y")';
                $groupby = 'DATE_FORMAT(groupTime, "%M %Y")';
                break;
            case "affs":
                $groupByCol = 'idUser';
                $groupby = 'idUser';
                break;
            case "days":
            default:
                $groupByCol = 'DATE_FORMAT(groupTime, "%Y-%m-%d")';
                $groupby = 'DATE_FORMAT(groupTime, "%Y-%m-%d")';
                break;
        }
        $sql =
            '
            SELECT
                '.$groupByCol.' as groupByCol,
                SUM(commissionSum) as commissionSum,
                SUM(transCount) as transCount,
                transStatus
            FROM
                smp_transactionstat
            WHERE
                (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
            GROUP BY
                transStatus,
                '.$groupby.
            //$pager['orderSQL'] .
            $pager['limitSQL'];
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        $transactionsData = $stmt->fetchAll();

        $sql =
            '
            SELECT
                '.$groupByCol.' as groupByCol,
                SUM(rawCount) as clicksCount,
                SUM(uniqCount) as uniqClicksCount
            FROM
                smp_clickstat
            WHERE
                groupType = "D" AND
                (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
            GROUP BY
                '.$groupby.
            $pager['orderSQL'] .
            $pager['limitSQL'];
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        $clicksData = $stmt->fetchAll();
        //var_dump($clicksData);

        $statsData = array();
        foreach ($transactionsData as $item)
        {
            $statsData[$item["groupByCol"]]["groupByCol"] = $item["groupByCol"];
            $statsData[$item["groupByCol"]]["commissionSum" + $item["transStatus"]] = $item["commissionSum"];
            $statsData[$item["groupByCol"]]["transCount" + $item["transStatus"]] = $item["transCount"];

            // проссумировать коммиссии и количество транзакций чтобы получить общие значения, независимые от статуса, т.е ВСЕГО
            $statsData[$item["groupByCol"]]["commissionSumAll"] = isset($statsData[$item["groupByCol"]]["commissionSumAll"]) ? $statsData[$item["groupByCol"]]["commissionSumAll"] : 0 + $item["commissionSum"];
            $statsData[$item["groupByCol"]]["transCountAll"] = isset($statsData[$item["groupByCol"]]["transCount"]) ? $statsData[$item["groupByCol"]]["transCount"] : 0 + $item["transCount"];
        }

        foreach ($clicksData as $item)
        {
            $statsData[$item["groupByCol"]]["groupByCol"] = $item["groupByCol"];
            $statsData[$item["groupByCol"]]["clicksCount"] = $item["clicksCount"];
            $statsData[$item["groupByCol"]]["uniqClicksCount"] = $item["uniqClicksCount"];
        }

        $statsDataNew = array();
        foreach ($statsData as $groupByCol => $item)
        {
            // установить 0 если нет такого элемента массива
            $item["commissionSumA"] = isset($item["commissionSumA"]) ? $item["commissionSumA"] : 0;
            $item["commissionSumP"] = isset($item["commissionSumP"]) ? $item["commissionSumP"] : 0;
            $item["commissionSumD"] = isset($item["commissionSumD"]) ? $item["commissionSumD"] : 0;
            $item["commissionSumAll"] = isset($item["commissionSumAll"]) ? $item["commissionSumAll"] : 0;
            $item["transCountA"] = isset($item["transCountA"]) ? $item["transCountA"] : 0;
            $item["transCountP"] = isset($item["transCountP"]) ? $item["transCountP"] : 0;
            $item["transCountD"] = isset($item["transCountD"]) ? $item["transCountD"] : 0;
            $item["transCountAll"] = isset($item["transCountAll"]) ? $item["transCountAll"] : 0;
            $item["clicksCount"] = isset($item["clicksCount"]) ? $item["clicksCount"] : 0;
            $item["uniqClicksCount"] = isset($item["uniqClicksCount"]) ? $item["uniqClicksCount"] : 0;

            // предотвратить деление на 0
            if (!empty($item["uniqClicksCount"]))
            {
                $item["epc"] = $item["commissionSumA"] / $item["uniqClicksCount"];
            }
            else
            {
                $item["epc"] = 0;
            }

            if (!empty($item["transCountA"]))
            {
                $item["cr"] = $item["clicksCount"] / $item["transCountA"];   // ? A
            }
            else
            {
                $item["cr"] = 0;
            }

            if (!empty($item["clicksCount"]))
            {
                $item["crP"] = round($item["transCountA"] / $item["clicksCount"] * 100);
            }
            else
            {
                $item["crP"] = 0;
            }

            if (!empty($item["transCountAll"]))
            {
                $item["approvedPerc"] = $item["transCountA"] / $item["transCountAll"];
                $item["approvedWithPendPerc"] = ($item["transCountA"] + $item["transCountP"]) / $item["transCountAll"];
            }
            else
            {
                $item["approvedPerc"] = 0;
                $item["approvedWithPendPerc"] = 0;
            }
            $statsDataNew[] = $item;
        }
        return $statsDataNew;
    }

    /**
     * Получить статистику учитывая фильтр и параметр группировки по аффам
     * @param $form данные формы фильтра
     * @return mixed
     */
    public function getStatsAffs(&$pager, $form = false)
    {
        $db = Zend_Registry::get('db');

        $where = array();
        $data = array();
        if (!empty($form))
        foreach ($form as $filter_key => $filter_value)
        {
            if (!empty($filter_value))
            {
                $field = str_replace('search_', '', $filter_key);
                switch ($field)
                {
                    case "dateperiod":
                        $available_periods = array("1 WEEK", "1 MONTH", "all", "custom");

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
                            $where[] =  'groupTime BETWEEN CURDATE() - INTERVAL '.$filter_value.' AND CURDATE() ';
                        }
                        break;
                    case "datefrom":
                        $where[] =  'groupTime >= :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "dateto":
                        $where[] =  'groupTime  <= DATE_ADD(:'.$field.', INTERVAL 1 DAY)';
                        $data[$field] = $filter_value;
                        break;
                    case "idAff":
                        $where[] =  'idUser = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "idRecl":
                        $where[] =  'idRecl = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "idCampaign":
                        $where[] =  'idCampaign = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "idLanding":
                        $where[] =  'idLanding = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                }
            }
        }
        $sortField = $pager["sortField"];

        $idUsers = array();
        if ($sortField == "clicksCount" || $sortField == "uniqClicksCount")
        {
                $sql =
                    '
                    SELECT
                        idUser,
                        SUM(rawCount) as clicksCount,
                        SUM(uniqCount) as uniqClicksCount
                    FROM
                        smp_clickstat
                    WHERE
                        (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
                    GROUP BY
                        idUser'
                    .
                    $pager["orderSQL"].
                    $pager['limitSQL'];
                $idUsers = $db->fetchCol($sql, $data);
        }
        if ($sortField == "transCountAll")
        {
                $sql =
                    '
                    SELECT
                        idUser,
                        SUM(commissionSum) as commissionSum,
                        SUM(transCount) as transCount
                    FROM
                        smp_transactionstat
                    WHERE
                        (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
                    GROUP BY
                        idUser'
                    .
                    $pager["orderSQL"].
                    $pager['limitSQL'];
                $idUsers = $db->fetchCol($sql, $data);
        }
        if ($sortField == "transCountA" || $sortField == "transCountP" || $sortField == "transCountD")
        {
                $sql =
                    '
                    SELECT
                        idUser,
                        SUM(commissionSum) as commissionSum,
                        SUM(transCount) as transCount
                    FROM
                        smp_transactionstat
                    WHERE
                        transStatus = "'.str_replace("transCount", "", $sortField).'" AND
                        (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
                    GROUP BY
                        idUser'
                    .
                    $pager["orderSQL"].
                    $pager['limitSQL'];
                $idUsers = $db->fetchCol($sql, $data);
        }

        if (empty($idUsers))
        {
            return false;
        }
        $sql =
            '
            SELECT
                idUser,
                login,
                name
            FROM
                smp_user
            WHERE
                idUser IN ('.implode(",", $idUsers).')';
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        $users = $stmt->fetchAll();

        $usersAssoc = array();
        foreach ($users as $user)
        {
            $usersAssoc[$user["idUser"]] = $user["login"] . (!empty($user["name"]) ? " (".$user["name"].")" : '');
        }

        $sql =
            '
            SELECT
                idUser as groupByCol,
                SUM(commissionSum) as commissionSum,
                SUM(transCount) as transCount,
                transStatus
            FROM
                smp_transactionstat
            WHERE
                (' . (!empty($where) ? implode(' AND ', $where) : 1) . ') AND
                idUser IN ('.implode(",", $idUsers).')
            GROUP BY
                transStatus,
                idUser'.
            $pager['limitSQL'];
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        $transactionsData = $stmt->fetchAll();

        $sql =
            '
            SELECT
                idUser as groupByCol,
                SUM(rawCount) as clicksCount,
                SUM(uniqCount) as uniqClicksCount
            FROM
                smp_clickstat
            WHERE
                groupType = "D" AND
                (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
            GROUP BY
                idUser'.
            $pager['limitSQL'];
        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        $clicksData = $stmt->fetchAll();
        //var_dump($clicksData);

        $statsData = array();
        foreach ($transactionsData as $item)
        {
            $statsData[$item["groupByCol"]]["groupByCol"] = $usersAssoc[$item["groupByCol"]];
            $statsData[$item["groupByCol"]]["commissionSum" + $item["transStatus"]] = $item["commissionSum"];
            $statsData[$item["groupByCol"]]["transCount" + $item["transStatus"]] = $item["transCount"];

            // проссумировать коммиссии и количество транзакций чтобы получить общие значения, независимые от статуса, т.е ВСЕГО
            $statsData[$item["groupByCol"]]["commissionSumAll"] = isset($statsData[$item["groupByCol"]]["commissionSumAll"]) ? $statsData[$item["groupByCol"]]["commissionSumAll"] : 0 + $item["commissionSum"];
            $statsData[$item["groupByCol"]]["transCountAll"] = isset($statsData[$item["groupByCol"]]["transCount"]) ? $statsData[$item["groupByCol"]]["transCount"] : 0 + $item["transCount"];
        }

        foreach ($clicksData as $item)
        {
            $statsData[$item["groupByCol"]]["groupByCol"] = $usersAssoc[$item["groupByCol"]];
            $statsData[$item["groupByCol"]]["clicksCount"] = $item["clicksCount"];
            $statsData[$item["groupByCol"]]["uniqClicksCount"] = $item["uniqClicksCount"];
        }

        $statsDataNew = array();
        foreach ($idUsers as $idUser)
        {
            $item = $statsData[$idUser];

            // установить 0 если нет такого элемента массива
            $item["commissionSumA"] = isset($item["commissionSumA"]) ? $item["commissionSumA"] : 0;
            $item["commissionSumP"] = isset($item["commissionSumP"]) ? $item["commissionSumP"] : 0;
            $item["commissionSumD"] = isset($item["commissionSumD"]) ? $item["commissionSumD"] : 0;
            $item["commissionSumAll"] = isset($item["commissionSumAll"]) ? $item["commissionSumAll"] : 0;
            $item["transCountA"] = isset($item["transCountA"]) ? $item["transCountA"] : 0;
            $item["transCountP"] = isset($item["transCountP"]) ? $item["transCountP"] : 0;
            $item["transCountD"] = isset($item["transCountD"]) ? $item["transCountD"] : 0;
            $item["transCountAll"] = isset($item["transCountAll"]) ? $item["transCountAll"] : 0;
            $item["clicksCount"] = isset($item["clicksCount"]) ? $item["clicksCount"] : 0;
            $item["uniqClicksCount"] = isset($item["uniqClicksCount"]) ? $item["uniqClicksCount"] : 0;

            // предотвратить деление на 0
            if (!empty($item["uniqClicksCount"]))
            {
                $item["epc"] = $item["commissionSumA"] / $item["uniqClicksCount"];
            }
            else
            {
                $item["epc"] = 0;
            }

            if (!empty($item["transCountA"]))
            {
                $item["cr"] = $item["clicksCount"] / $item["transCountA"];   // ? A
            }
            else
            {
                $item["cr"] = 0;
            }

            if (!empty($item["clicksCount"]))
            {
                $item["crP"] = round($item["transCountA"] / $item["clicksCount"] * 100);
            }
            else
            {
                $item["crP"] = 0;
            }

            if (!empty($item["transCountAll"]))
            {
                $item["approvedPerc"] = $item["transCountA"] / $item["transCountAll"];
                $item["approvedWithPendPerc"] = ($item["transCountA"] + $item["transCountP"]) / $item["transCountAll"];
            }
            else
            {
                $item["approvedPerc"] = 0;
                $item["approvedWithPendPerc"] = 0;
            }
            $statsDataNew[] = $item;
        }
        return $statsDataNew;
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

}