<?php

class CampaignsModel
{
    /**
     * Достать офферов учитывая фильтр
     * @param $pager
     * @param $form данные формы фильтра
     * @return mixed
     */
    public function getCampaigns(&$pager, $form = false)
    {
        $db = Zend_Registry::get('db');

        $join = array();
        $where = array();
        $data = array();

        if (!empty($form))
        foreach ($form as $filter_key=>$filter_value)
        {
            $exp = '/([a-zA-Z]{2}\,)*/';

            if (!empty($filter_value))
            {
                $field = str_replace('search_', '', $filter_key);
                switch ($field)
                {
                    case "countries":
                        //$filter_value = trim($filter_value, ', ');
                        //$filter_value = str_replace(', ', '|', $filter_value);
                        $where[] =  $field. ' REGEXP "'.implode("|", $filter_value).'"';
                        break;
                    case "campaignStatus":
                        $available_statuses = array('E', 'D');
                        // проверить являются ли значения стутсов одним из допустимых
                        if (count(array_unique(array_merge($available_statuses, $filter_value))) == count($available_statuses))
                        {
                            // добавить условие если выбраны не все статусы
                            if (count($filter_value) < count($available_statuses))
                            {
                                $where[] =  'campaignStatus IN ("' . implode('","', $filter_value).'")';
                            }
                        }
                        break;
                    case "targets":
                        $available_statuses = $this->getTargets();
                        // проверить являются ли значения целей кампании одним из допустимых
                        if (count(array_unique(array_merge($available_statuses, $filter_value))) == count($available_statuses))
                        {
                            // добавить условие если выбраны не все цели
                            if (count($filter_value) < count($available_statuses))
                            {
                                $where[] =  $field. ' REGEXP "'.implode('|', $filter_value).'"';
                                break;
                                //$where[] =  'targets IN ("' . implode('","', $filter_value).'")';
                            }
                        }
                        break;
                    case "campaignType":
                        $available_statuses = array('P', 'R', 'I');
                        // проверить являются ли значения типов одним из допустимых
                        if (count(array_unique(array_merge($available_statuses, $filter_value))) == count($available_statuses))
                        {
                            // добавить условие если выбраны не все типы
                            if (count($filter_value) < count($available_statuses))
                            {
                                $where[] =  'campaignType IN ("' . implode('","', $filter_value).'")';
                            }
                        }
                        break;
                    case "idCampaign":
                        $where[] =  'smp_campaign.' . $field. ' = :' . $field;
                        $data[$field] = $filter_value;
                        break;
                    case "idRecl":
                        $where[] =  'smp_campaign.idCampaign IN (SELECT smp_reclcampaign.idCampaign FROM smp_reclcampaign WHERE smp_reclcampaign.' . $field. ' = :' . $field.')';
                        $data[$field] = $filter_value;
                        break;
                }
            }
        }
        $pager['total'] = $this->getCampaignsCount($where, $data);

        if (empty($pager['orderSQL']))
        {
            $pager['orderSQL'] = 'ORDER BY
                createDate DESC';
        }
        $sql =
            'SELECT
                smp_campaign.*
            FROM
                smp_campaign
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
     * Достает количество кампаний
     * @param $where - массив условий
     * @param $data - массив значений условий
     */
    private function getCampaignsCount($where, $data)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                count(*)
            FROM
                smp_campaign
            WHERE
                (' . (!empty($where) ? implode(' AND ', $where) : 1) . ')
            ';
        return $db->fetchOne($sql, $data);
    }


    /**
     * Получить данные кампании
     * @return mixed
     */
    public function getCampaign($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                *
            FROM
                smp_campaign
            WHERE
                idCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCampaign));
        return $stmt->fetch();
    }


    /**
     * Проверить является ли кампании приватной
     * @return mixed
     */
    public function isCampaignPrivate($idCampaign)
    {
        $campaign = $this->getCampaign($idCampaign);
        return (($campaign["campaignType"] == "R") ? true : false);
    }


    /**
     * Получить страны для списка с автодополнением
     * @return mixed
     */
    public function getCountriesList($str)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                countryCode as id,
                countryCode as value,
                countryCode as label
            FROM
                smp_country
            WHERE
                countryCode LIKE ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($str."%"));
        return $stmt->fetchAll();
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
     * Получить список целей
     * @return mixed
     */
    public function getTargets()
    {
        return array('sale', 'appsale', 'validsale', 'paydsale', 'reg', 'level', 'effectreg', 'activeplayer');
    }


    /**
     * список источников трафика
     */
    public function getSources()
    {
        $sources = array();

        $sources[] = array('name' => 'веб-сайты', 'code' => 1);
        $sources[] = array('name' => 'дорвеи', 'code' => 2);
        $sources[] = array('name' => 'контекстная реклама', 'code' => 4);
        $sources[] = array('name' => 'контекстная реклама на бренд', 'code' => 8);
        $sources[] = array('name' => 'тизерная/баннерная реклама', 'code' => 16);
        $sources[] = array('name' => 'соцсети: таргетированная реклама', 'code' => 32);
        $sources[] = array('name' => 'соцсети: паблики, игры, приложения', 'code' => 64);
        $sources[] = array('name' => 'email-рассылка', 'code' => 128);
        $sources[] = array('name' => 'CashBack', 'code' => 256);
        $sources[] = array('name' => 'ClickUnder/PopUnder', 'code' => 512);
        $sources[] = array('name' => 'Мотивированный трафик', 'code' => 1024);
        return $sources;
    }


    /**
     * изменить статус оффера
     * @param $idCampaign
     * @param $changeTo новый статус
     */
    public function changeStatus($idCampaign, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_campaign
            SET
              campaignStatus = ?
            WHERE
              idCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idCampaign));
    }


    /**
     * изменить тип оффера
     * @param $idCampaign
     * @param $changeTo новый типа оффера
     */
    public function changeType($idCampaign, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_campaign
            SET
              campaignType = ?
            WHERE
              idCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idCampaign));
    }

    /**
     * Добавление оффера
     * @param $data
     */
    public function addCampaign($data)
    {
        $data['idCampaign'] = createUniqueId(6, 'smp_campaign', 'idCampaign');
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
                smp_campaign
            SET ' . implode(' , ', $set) . '
            ';


        $stmt = $db->prepare($sql);
        $stmt->execute($setData);
        return $data['idCampaign'];
    }


    /**
     * Редактирование оффера
     * @param $idCampaign
     * @param $data
     */
    public function editCampaign($idCampaign, $data)
    {
        $db = Zend_Registry::get('db');

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }
        $setData["idCampaign"] = $idCampaign;

         $sql = '
            UPDATE
                smp_campaign
            SET ' . implode(' , ', $set) . '
            WHERE
                idCampaign = :idCampaign
            ';


        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }

    /**
     * Добавление реклов оффера
     * @param $data
     */
    public function addCampaignRecls($idCampaign, $idRecls)
    {
        $db = Zend_Registry::get('db');

        $setData = array();
        $values = array();

        foreach ($idRecls as $idRecl)
        {
            $values[] = '(?, ?)';

            $setData[] = $idCampaign;
            $setData[] = $idRecl;
        }

        $sql = '
            INSERT INTO
                smp_reclcampaign
                (idCampaign, idRecl)
            VALUES
                ' . implode(' , ', $values) . '
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute($setData);
        return $db->lastInsertId();
    }

    /**
     * Обновить поля кампании которые хранят информацию агрегирующую по кампании, например список стран ()зависит от стран которые выбраны в коммиссиях
     * @param $data
     */
    public function updateCampaignAggregateFields($idCampaign)
    {
        $commissions = $this->getCampaignCommissions($idCampaign);

        $countries = array();
        $targets = array();
        foreach ($commissions as $commission)
        {
            $commissionCountries = explode(";", $commission["countries"]);
            $countries = array_unique(array_merge($commissionCountries, $countries));

            $targets = array_unique(array_merge(array($commission["target"]), $targets));
        }
        $this->editCampaign($idCampaign, array("targets" => implode(";", $targets), "countries" => implode(";", $countries)));
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
                *
            FROM
                smp_commission
            WHERE
                idCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCampaign));
        return $stmt->fetchAll();
    }


    /**
     * Получить пользователей для списка с автодополнением
     * @param $idRole айди роли
     * @param $term введенная строка в поле в автодополнением
     * @return mixed
     */
    public function getCampaignsList($term, $idRecl = false, $activeOnly = false)
    {
        $db = Zend_Registry::get('db');

        $join = '';
        $where = '';
        $data = array();
        if ($activeOnly)
        {
            $where = 'campaignStatus = "E" AND';
        }
        if ($idRecl)
        {
            $join = '
            INNER JOIN
                smp_reclcampaign as rc
            ON
                rc.idCampaign = c.idCampaign
            ';
            $where = 'rc.idRecl = ? AND';
            $data[] = $idRecl;
        }

        $data[] = $term."%";

        $sql =
            'SELECT
                c.idCampaign as id,
                c.campaignName as value,
                c.campaignName as label,
                c.campaignStatus
            FROM
                smp_campaign as c
            '.$join.'
            WHERE
                '.$where.'
                c.campaignName LIKE ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute($data);
        return $stmt->fetchAll();
    }


}