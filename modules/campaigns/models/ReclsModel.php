<?php

class ReclsModel
{
    /**
     * Получить реклов для списка с автодополнением
     * @return mixed
     */
    public function getReclsList($str)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                idRecl as id,
                reclName as value,
                reclName as label
            FROM
                smp_recl
            WHERE
                reclStatus = "E" AND
                reclName LIKE ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($str."%"));
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
                idCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        $stmt->execute(array($idCampaign));
        return $stmt->fetchAll();
    }

    /**
     * Получить id реклов кампании
     * @return mixed
     */
    public function getCampaignReclsIds($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql =
            'SELECT
                idRecl
            FROM
                smp_reclcampaign
            WHERE
                idCampaign = ?
            ';

        return $db->fetchCol($sql, array($idCampaign));
    }


    /**
     * изменить количество допустимых заявок в день
     * @param $idReclCampaign
     * @param $transCountPerDay новое количество допустимых заявок в день
     */
    public function editTransCount($idReclCampaign, $transCountPerDay)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
                smp_reclcampaign
            SET
                transCountPerDay = ?
            WHERE
                idReclCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($transCountPerDay, $idReclCampaign));
    }

    /**
     * сбросить коеффициенты транзакций для всех реклов кампании
     * @param $idCampaign
     */
    public function resetCampaignReclsCoeffs($idCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            UPDATE
                smp_reclcampaign
            SET
                transCoeff = 0
            WHERE
                idCampaign = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idCampaign));
    }


    /**
     * добавить рекламодателя к кампании и количество обрабатываемых заявок в день
     * @param $idCampaign
     * @param $idRecl
     * @param $transCountPerDay количество допустимых заявок в день
     */
    public function addReclCampaign($idCampaign, $idRecl, $transCountPerDay)
    {
        $db = Zend_Registry::get('db');



        $sql = '
            INSERT INTO
                smp_reclcampaign
            SET
                idRecl = ?,
                idCampaign = ?,
                transCountPerDay = ?
            ';

        $stmt = $db->prepare($sql);
        return $stmt->execute(array($idRecl, $idCampaign, $transCountPerDay));
    }


    /**
     * достать рекламодателя кампании
     * @param $idCampaign
     * @param $idRecl
     */
    public function getReclCampaign($idCampaign, $idRecl)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            SELECT
                *
            FROM
                smp_reclcampaign
            WHERE
              idRecl = ? AND
              idCampaign = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idRecl, $idCampaign));
        return $stmt->fetch();
    }

    /**
     * достать рекламодателя кампании по айди записи
     * @param $idCampaign
     * @param $idRecl
     */
    public function getReclCampaignById($idReclCampaign)
    {
        $db = Zend_Registry::get('db');

        $sql = '
            SELECT
                *
            FROM
                smp_reclcampaign
            WHERE
              idReclCampaign = ?
            ';
        $stmt = $db->prepare($sql);
        $stmt->execute(array($idReclCampaign));
        return $stmt->fetch();
    }
}