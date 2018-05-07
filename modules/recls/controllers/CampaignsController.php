<?php

class Recls_CampaignsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('main_title', $this->_valid->getMessage('title'));
    }

    /**
     * отображения списка кампаний рекла
     */
    public function indexAction()
    {
        $cnf = Zend_Registry::get('cnf');
        $idRecl = $this->getRequest()->getParam('id', '');
        $campaigns = $this->_model->getReclCampaigns($idRecl);

        $idCampaigns = array();
        foreach ($campaigns as $campaign)
        {
            $idCampaigns[] = $campaign["idCampaign"];
        }

        Zend_Loader::loadClass("PaymentsModel", $this->_cnf->path->modulesFront . "recls" . $this->_cnf->path->models);
        $_paymentsModel = new PaymentsModel;

        $reclCampaignsPaymentsSum = $_paymentsModel->getReclCampaignsPaymentsSum($idRecl, true, $idCampaigns);
        $reclCampaignsUnpayedTransactionsInfo = $_paymentsModel->getReclCampaignsUnpayedTransactionsInfo($idRecl, true, $idCampaigns);

        foreach ($campaigns as $key => $campaign)
        {
            $campaigns[$key]["payedTransactionsInfo"] = (isset($reclCampaignsPaymentsSum[$campaign["idCampaign"]]) ? $reclCampaignsPaymentsSum[$campaign["idCampaign"]]["payedSum"] : 0);
            $campaigns[$key]["unpayedTransactionsInfo"] = (isset($reclCampaignsUnpayedTransactionsInfo[$campaign["idCampaign"]]) ? $reclCampaignsUnpayedTransactionsInfo[$campaign["idCampaign"]]["commissionsSum"] : 0).' ('.(isset($reclCampaignsUnpayedTransactionsInfo[$campaign["idCampaign"]]) ? $reclCampaignsUnpayedTransactionsInfo[$campaign["idCampaign"]]["transactionsCount"] : 0).' транз.)';
        }

        $this->_view->assign('offersUrl', $cnf->url->offers);
        $this->_view->assign('idRecl', $idRecl);
        $this->_view->assign('campaigns', $campaigns);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idRecl' => $idRecl));
        exit;
    }

}