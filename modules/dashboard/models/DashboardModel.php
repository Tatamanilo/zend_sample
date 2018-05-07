<?php

class DashboardModel
{

    /**
     * Получить кампании рекла
     * @return mixed
     */
    public function getTransactionsInfo($transactionStatus = false, $period = '1 WEEK')
    {
        $db = Zend_Registry::get('db');

        $data = array();
        $where = '';

        if ($transactionStatus)
        {
            $where = 'transactionStatus = ? AND';
            $data[] = $transactionStatus;
        }

        $sql = '
            SELECT
                COUNT(idTransaction)
            FROM
                smp_transaction
            WHERE
                '.$where.'
                insertDate BETWEEN CURDATE()-INTERVAL '.$period.' AND CURDATE()
            ';
        return $db->fetchOne($sql, $data);
    }

}