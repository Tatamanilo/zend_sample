<?php

class PaymentsModel
{
    /**
     * Получить историю пополнения счета
     * @param $idRecl айди рекла
     * @param $pager
     * @return mixed
     */
    public function getAccountHistory($idRecl, &$pager)
    {
        $db = Zend_Registry::get('db');

        $pager['total'] = $this->_getAccountHistoryCount($idRecl);

        $sql =
            'SELECT
                *
            FROM
                smp_reclaccount
            WHERE
                idRecl = ?'.
            $pager['orderSQL'] .
            $pager['limitSQL'];


        $stmt = $db->prepare($sql);
        $stmt->execute(array($idRecl));
        return $stmt->fetchAll();
    }


    /**
     * Достает количество платежей на счет рекла
     * @param $idUser
     * @return mixed
     */
    private function _getAccountHistoryCount($idRecl)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                count(*)
            FROM
                smp_reclaccount
            WHERE
                idRecl = ?
            ';
        return $db->fetchOne($sql, array($idRecl));
    }


    /**
     * Добавление платежа на счет
     * @param $idCommission
     * @param $data
     */
    public function addAccountPayment($idRecl, $data)
    {
        $db = Zend_Registry::get('db');

        $data["idRecl"] = $idRecl;

        $set = array();
        $setData = array();
        foreach ($data as $key => $field)
        {
            $set[] = $key . ' = :' . $key;

            $setData[$key] = $field;
        }

         $sql = '
            INSERT INTO
                smp_reclaccount
            SET ' . implode(' , ', $set) . '
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute($setData);
    }

    /**
     * изменить статус подтверждения пополнения рекла счета
     * @param $idReclAccount
     * @param $changeTo новый статус
     */
    public function changeAccountPaymentApprove($idReclAccount, $changeTo)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_reclaccount
            SET
              approve = ?
            WHERE
              idReclAccount = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($changeTo, $idReclAccount));
    }


    /**
     * Достает общую сумму денег положенных на счет рекла и проверенных
     * @param $idRecl
     * @return mixed
     */
    public function getReclAccountTotalApprovedSum($idRecl)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                SUM(paySum)
            FROM
                smp_reclaccount
            WHERE
                idRecl = ? AND
                approve = 1
            ';
        return $db->fetchOne($sql, array($idRecl));
    }

    /**
     * Достает стоимость оплаченнных реклом транзакций из таблицы smp_reclpayment
     * @param $idRecl
     * @param $idCampaign если параметр указаан - то учитывать инфо по конкретной кампании
     * @return mixed
     */
    public function getReclPaymentsSum($idRecl, $idCampaign = false)
    {
        $db = Zend_Registry::get('db');


        $data = array();
        $where = '';

        if (!empty($idCampaign))
        {
            $where = 'idCampaign = ? AND';
            $data[] = $idCampaign;
        }
        $data[] = $idRecl;

        $sql ='
            SELECT
                SUM(paySum) as payedSum
            FROM
                smp_reclpayment
            WHERE
                '.$where.'
                idRecl = ?

            ';
        return $db->fetchOne($sql, $data);
    }

    /**
     * Достает количество и стоимость оплаченнных реклом транзакций
     * @param $idRecl
     * @param $idCampaign если параметр указаан - то учитывать инфо по конкретной кампании
     * @return mixed
     */
    public function getReclPayedTransactionsInfo($idRecl, $idCampaign = false)
    {
        $db = Zend_Registry::get('db');

        $data = array($idRecl);
        $where = '';

        if (!empty($idCampaign))
        {
            $where = 'idCampaign = ? AND';
            $data[] = $idCampaign;
        }
        $sql ='
            SELECT
                SUM(reclComission) as commissionsSum,
                COUNT(idTransaction) as transactionsCount
            FROM
                smp_transaction
            WHERE
                idRecl = ? AND
                '.$where.'
                transType = "sale" AND
                payedByRecl = "P" AND
                transactionStatus = "A"
            ';
        return $db->fetchOne($sql, $data);
    }

    /**
     * Достает количество и стоимость неоплаченнных реклом транзакций
     * @param $idRecl
     * @param $idCampaign если параметр указаан - то учитывать инфо по конкретной кампании
     * @return mixed
     */
    public function getReclUnpayedTransactionsInfo($idRecl, $idCampaign = false)
    {
        $db = Zend_Registry::get('db');

        $data = array($idRecl);
        $where = '';

        if (!empty($idCampaign))
        {
            $where = 'idCampaign = ? AND';
            $data[] = $idCampaign;
        }
        $sql ='
            SELECT
                SUM(reclComission) as commissionsSum,
                COUNT(idTransaction) as transactionsCount
            FROM
                smp_transaction
            WHERE
                idRecl = ? AND
                '.$where.'
                transType = "sale" AND
                payedByRecl = "U" AND
                transactionStatus = "A"
            ';
        return $db->fetchOne($sql, $data);
    }


    /**
     * Достает сумму денег у рекла на счету (сумма положенная на счет но не списанная пока на транзакции)
     * @param $idRecl
     * @return mixed
     */
    public function getReclOnAccount($idRecl)
    {
        $payed = $this->getReclPayedTransactionsInfo($idRecl);
        return $this->getReclAccountTotalApprovedSum($idRecl) -  $payed["commissionsSum"];
    }

    /**
     * Достает сумму денег у рекла на счету (сумма положенная на счет но не списанная пока на транзакции)
     * @param $idRecl
     * @return mixed
     */
    public function getReclBalance($idRecl)
    {
        $unpayed = $this->getReclUnpayedTransactionsInfo($idRecl);
        return $this->getReclOnAccount($idRecl) -  $unpayed["commissionsSum"];
    }


    /**
     * Достает общую сумму денег положенных на счет реклов и проверенных
     * @return mixed
     */
    public function getReclsAccountTotalApprovedSum($assoc = true, $idRecls = null)
    {
        $db = Zend_Registry::get('db');

        $where = '';
        if (!empty($idRecls))
        {
            $where = 'idRecl IN ('.implode(",", $idRecls).') AND';
        }

        $sql ='
            SELECT
                idRecl,
                SUM(paySum) as totalApprovedSum
            FROM
                smp_reclaccount
            WHERE
                '.$where.'
                approve = 1
            GROUP BY
                idRecl
            ';
        $data = $db->fetchAll($sql);

        if (!$assoc)
        {
            return $data;
        }

        $dataAssoc = array();
        foreach ($data as $key => $item)
        {
            $dataAssoc[$item["idRecl"]] = $item;
        }
        return $dataAssoc;
    }

    /**
     * Достает количество и стоимость оплаченнных реклами транзакций из таблицы smp_reclpayment
     * @param $assoc формировать ли ассоциативный по реклам массив
     * @param $idRecls если параметр указаан - то учитывать инфо по конкретным реклам
     * @return mixed
     */
    public function getReclsPaymentsSum($assoc = true, $idRecls = null)
    {
        $db = Zend_Registry::get('db');

        $where = '1';
        if (!empty($idRecls))
        {
            $where = 'idRecl IN ('.implode(",", $idRecls).')';
        }

        $sql ='
            SELECT
                idRecl,
                SUM(paySum) as payedSum
            FROM
                smp_reclpayment
            WHERE
                '.$where.'
            GROUP BY
                idRecl
            ';
        $data = $db->fetchAll($sql);

        if (!$assoc)
        {
            return $data;
        }

        $dataAssoc = array();
        foreach ($data as $key => $item)
        {
            $dataAssoc[$item["idRecl"]] = $item;
        }
        return $dataAssoc;
    }

    /**
     * Достает количество и стоимость оплаченнных реклами транзакций
     * @param $assoc формировать ли ассоциативный по реклам массив
     * @param $idRecls если параметр указаан - то учитывать инфо по конкретным реклам
     * @return mixed
     */
    public function getReclsPayedTransactionsInfo($assoc = true, $idRecls = null)
    {
        $db = Zend_Registry::get('db');

        $where = '';
        if (!empty($idRecls))
        {
            $where = 'idRecl IN ('.implode(",", $idRecls).') AND';
        }

        $sql ='
            SELECT
                idRecl,
                SUM(reclComission) as commissionsSum,
                COUNT(idTransaction) as transactionsCount
            FROM
                smp_transaction
            WHERE
                '.$where.'
                transType = "sale" AND
                payedByRecl = "P" AND
                transactionStatus = "A"
            GROUP BY
                idRecl
            ';
        $data = $db->fetchAll($sql);

        if (!$assoc)
        {
            return $data;
        }

        $dataAssoc = array();
        foreach ($data as $key => $item)
        {
            $dataAssoc[$item["idRecl"]] = $item;
        }
        return $dataAssoc;
    }

    /**
     * Достает количество и стоимость неоплаченнных реклами транзакций
     * @param $assoc формировать ли ассоциативный по реклам массив
     * @param $idRecls если параметр указаан - то учитывать инфо по конкретным реклам
     * @return mixed
     */
    public function getReclsUnpayedTransactionsInfo($assoc = true, $idRecls = null)
    {
        $db = Zend_Registry::get('db');

        $where = '';
        if (!empty($idRecls))
        {
            $where = 'idRecl IN ('.implode(",", $idRecls).') AND';
        }

        $sql ='
            SELECT
                idRecl,
                SUM(reclComission) as commissionsSum,
                COUNT(idTransaction) as transactionsCount
            FROM
                smp_transaction
            WHERE
                '.$where.'
                transType = "sale" AND
                payedByRecl = "U" AND
                transactionStatus = "A"
            GROUP BY
                idRecl
            ';
        $data = $db->fetchAll($sql);

        if (!$assoc)
        {
            return $data;
        }

        $dataAssoc = array();
        foreach ($data as $key => $item)
        {
            $dataAssoc[$item["idRecl"]] = $item;
        }
        return $dataAssoc;
    }


    /**
     * Достает количество и стоимость оплаченнных реклом транзакций из таблицы smp_reclpayment по кампнаиям
     * @param $idRecl рекл
     * @param $assoc формировать ли ассоциативный по кампаниям массив
     * @param $idCampaigns если параметр указаан - то учитывать инфо по конкретным кампании
     * @return mixed
     */
    public function getReclCampaignsPaymentsSum($idRecl, $assoc = true, $idCampaigns = null)
    {
        $db = Zend_Registry::get('db');

        $where = '';
        if (!empty($idCampaigns))
        {
            $where = 'idCampaign IN ("'.implode('","', $idCampaigns).'") AND';
        }

        $sql ='
            SELECT
                idCampaign,
                SUM(paySum) as payedSum
            FROM
                smp_reclpayment
            WHERE
                '.$where.'
                idRecl = ?
            GROUP BY
                idCampaign
            ';
        $data = $db->fetchAll($sql, array($idRecl));

        if (!$assoc)
        {
            return $data;
        }

        $dataAssoc = array();
        foreach ($data as $key => $item)
        {
            $dataAssoc[$item["idCampaign"]] = $item;
        }
        return $dataAssoc;
    }

    /**
     * Достает количество и стоимость оплаченнных реклом транзакций по кампнаиям
     * @param $idRecl рекл
     * @param $assoc формировать ли ассоциативный по кампаниям массив
     * @param $idCampaigns если параметр указаан - то учитывать инфо по конкретным кампании
     * @return mixed
     */
    public function getReclCampaignsPayedTransactionsInfo($idRecl, $assoc = true, $idCampaigns = null)
    {
        $db = Zend_Registry::get('db');

        $where = '';
        if (!empty($idCampaigns))
        {
            $where = 'idCampaign IN ("'.implode('","', $idCampaigns).'") AND';
        }

        $sql ='
            SELECT
                idCampaign,
                SUM(reclComission) as commissionsSum,
                COUNT(idTransaction) as transactionsCount
            FROM
                smp_transaction
            WHERE
                '.$where.'
                idRecl = ? AND
                transType = "sale" AND
                payedByRecl = "P" AND
                transactionStatus = "A"
            GROUP BY
                idCampaign
            ';
        $data = $db->fetchAll($sql, array($idRecl));

        if (!$assoc)
        {
            return $data;
        }

        $dataAssoc = array();
        foreach ($data as $key => $item)
        {
            $dataAssoc[$item["idCampaign"]] = $item;
        }
        return $dataAssoc;
    }

    /**
     * Достает количество и стоимость неоплаченнных реклом транзакций по кампнаиям
     * @param $idRecl рекл
     * @param $assoc формировать ли ассоциативный по кампаниям массив
     * @param $idCampaigns если параметр указаан - то учитывать инфо по конкретным кампании
     * @return mixed
     */
    public function getReclCampaignsUnpayedTransactionsInfo($idRecl, $assoc = true, $idCampaigns = null)
    {
        $db = Zend_Registry::get('db');

        $where = '';
        if (!empty($idCampaigns))
        {
            $where = 'idCampaign IN ("'.implode('","', $idCampaigns).'") AND';
        }

        $sql ='
            SELECT
                idCampaign,
                SUM(reclComission) as commissionsSum,
                COUNT(idTransaction) as transactionsCount
            FROM
                smp_transaction
            WHERE
                '.$where.'
                idRecl = ? AND
                transType = "sale" AND
                payedByRecl = "U" AND
                transactionStatus = "A"
            GROUP BY
                idCampaign
            ';
        $data = $db->fetchAll($sql, array($idRecl));

        if (!$assoc)
        {
            return $data;
        }

        $dataAssoc = array();
        foreach ($data as $key => $item)
        {
            $dataAssoc[$item["idCampaign"]] = $item;
        }
        return $dataAssoc;
    }



    /**
     * Получить историю оплат транзакций
     * @param $idRecl айди рекла
     * @param $idCampaign айди кампании
     * @param $pager
     * @return mixed
     */
    public function getPaymentsHistory($idRecl, $idCampaign, &$pager)
    {
        $db = Zend_Registry::get('db');

        $pager['total'] = $this->_getPaymentsHistoryCount($idRecl, $idCampaign);

        $sql =
            'SELECT
                *
            FROM
                smp_reclpayment
            WHERE
                idRecl = ? AND
                idCampaign = ?
                '.
            $pager['orderSQL'] .
            $pager['limitSQL'];


        $stmt = $db->prepare($sql);
        $stmt->execute(array($idRecl, $idCampaign));
        return $stmt->fetchAll();
    }


    /**
     * Достает количество платежей на счет рекла
     * @param $idUser
     * @return mixed
     */
    private function _getPaymentsHistoryCount($idRecl, $idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                count(*)
            FROM
                smp_reclpayment
            WHERE
                idRecl = ? AND
                idCampaign = ?
            ';
        return $db->fetchOne($sql, array($idRecl, $idCampaign));
    }


    /**
     * Добавление платежа на счет
     * @param $idCommission
     * @param $data
     */
    public function addTransactionPayment($idRecl, $idCampaign, $data)
    {
        $db = Zend_Registry::get('db');

        $data["idRecl"] = $idRecl;
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
                smp_reclpayment
            SET ' . implode(' , ', $set) . '
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute($setData);
        return $db->lastInsertId();
    }


    /**
     * Достает неоплаченнные реклом транзакции по кампании
     * @param $idRecl
     * @param $idCampaign
     * @return mixed
     */
    public function getReclCampaignUnpayedTransactions($idRecl, $idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql ='
            SELECT
                *
            FROM
                smp_transaction
            WHERE
                idRecl = ? AND
                idCampaign = ? AND
                transType = "sale" AND
                payedByRecl = "U" AND
                transactionStatus = "A"
            ORDER BY
                insertDate DESC
            ';
        return $db->fetchAll($sql, array($idRecl, $idCampaign));
    }

    /**
     * изменить статус транзакций как оплаченные реклом, установить поле айди записи оплаты
     * @param $idTransactions
     * @param $idReclPayment
     */
    public function markTransactionsPayedByRecl($idTransactions, $idReclPayment)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
              smp_transaction
            SET
              payedByRecl = "P",
              idReclPayment = ?
            WHERE
              idTransaction IN ('.implode("," ,$idTransactions).')
            ';
        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idReclPayment));
    }
}
