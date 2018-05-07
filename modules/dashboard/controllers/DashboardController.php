<?php

class Dashboard_DashboardController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('main_title', $this->_valid->getMessage('title'));
        $this->_view->assign('sub_title', $this->_valid->getMessage('sub_title'));
    }

    /**
     * отображения списка кампаний рекла
     */
    public function indexAction()
    {
        $transactionsCount = array();
        $transactionsCount["A"] = $this->_model->getTransactionsInfo("A", '1 YEAR');
        $transactionsCount["P"] = $this->_model->getTransactionsInfo("P", '1 YEAR');
        $transactionsCount["D"] = $this->_model->getTransactionsInfo("D", '1 YEAR');
        $transactionsCount["all"] = $this->_model->getTransactionsInfo(false, '1 YEAR');

        $approvedRate = round(($transactionsCount["A"] / $transactionsCount["all"]) * 100);
        $approvedPendingRate = round((($transactionsCount["A"] + $transactionsCount["P"]) / $transactionsCount["all"]) * 100, 2);


        //var_dump($transactionsCount);
        //exit;
        $this->_view->assign('approvedRate', $approvedRate);
        $this->_view->assign('approvedPendingRate', $approvedPendingRate);
        $this->_view->assign('transactionsCount', $transactionsCount);
        $this->_view->addView($this->_defaultTpl, $this->_mca);
    }

}