<?php

class Stats_StatsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('main_title', $this->_valid->getMessage('title'));
    }

    /**
     * отображение статистики
     */
    public function indexAction()
    {
        $this->_view->addView($this->_defaultTpl, $this->_mca);
    }


    /**
     * Возвращает json список статистики
     */
    public function statsAction()
    {
        $pager = pagerPrepareDT($this->getRequest());
        $form = $this->getRequest()->getParam('form', 0);
        /*
        if (!empty($form))
        if (!$this->_valid->validateForm($form))
        {
            $data = pagerDataPrepareDT(array(), $pager);
            echo json_encode(array(
                'result' => 0,
                'error_type' => 'form',
                'errors' => $this->_valid->getInput()->getMessages(),
                'data' => $data));
            exit;
        } */


        $stats = $this->_model->getStats($pager, $form);
        $data = pagerDataPrepareDT($stats, $pager);

        echo json_encode(array(
            'result' => 1,
            'data' => $data));
        exit;
    }

    /**
     * Возвращает json список статистики
     */
    public function statsaffsAction()
    {
        $pager = pagerPrepareDT($this->getRequest());
        $form = $this->getRequest()->getParam('form', 0);

        $iSortCol_0 = $this->getRequest()->getParam('iSortCol_0', false);
        $sortClause = '';
        if ($iSortCol_0 !== false)
        {
            $sortField = $this->getRequest()->getParam('mDataProp_'.$iSortCol_0, '');
            $sortDest = $this->getRequest()->getParam('sSortDir_0', 'ASC');

            $pager["sortField"] = $sortField;

            switch($sortField)
            {
                case "clicksCount":
                case "uniqClicksCount":
                    $pager["orderSQL"] = ' ORDER BY ' . $sortField . ' ' . $sortDest;

                    break;
                case "transCountAll":
                case "transCountA":
                case "transCountP":
                case "transCountD":
                    $pager["orderSQL"] = ' ORDER BY transCount ' . $sortDest;
                    break;
            }
        }
        /*
        if (!empty($form))
        if (!$this->_valid->validateForm($form))
        {
            $data = pagerDataPrepareDT(array(), $pager);
            echo json_encode(array(
                'result' => 0,
                'error_type' => 'form',
                'errors' => $this->_valid->getInput()->getMessages(),
                'data' => $data));
            exit;
        } */


        $stats = $this->_model->getStatsAffs($pager, $form);
        $data = pagerDataPrepareDT($stats, $pager);

        echo json_encode(array(
            'result' => 1,
            'data' => $data));
        exit;
    }
}