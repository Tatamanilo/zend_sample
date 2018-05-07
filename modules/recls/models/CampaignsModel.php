<?php

class CampaignsModel
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

}