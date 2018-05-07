<?php

class Recls_ReclsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('main_title', $this->_valid->getMessage('title'));
    }

    /**
     * отображения списка реклов
     */
    public function indexAction()
    {
        //$this->_view->assign('recls', $this->_model->getRecls());
        $this->_view->addView($this->_defaultTpl, $this->_mca);
    }

    /**
     * Возвращает json список реклов
     */
    public function reclsAction()
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


        $users = $this->_model->getRecls($pager, $form);

        foreach ($users as $user)
        {
            $idUsers[] = $user["idRecl"];
        }

        Zend_Loader::loadClass("PaymentsModel", $this->_cnf->path->modulesFront . "recls" . $this->_cnf->path->models);
        $_paymentsModel = new PaymentsModel;

        $reclsAccountTotalApprovedSum = $_paymentsModel->getReclsAccountTotalApprovedSum(true, $idUsers);
        $reclsPaymentsSum = $_paymentsModel->getReclsPaymentsSum(true, $idUsers);
        $reclsUnpayedTransactionsInfo = $_paymentsModel->getReclsUnpayedTransactionsInfo(true, $idUsers);

        foreach ($users as $key => $user)
        {
            $users[$key]["onAccount"] = (isset($reclsAccountTotalApprovedSum[$user["idRecl"]]) ? $reclsAccountTotalApprovedSum[$user["idRecl"]]["totalApprovedSum"] : 0) - (isset($reclsPaymentsSum[$user["idRecl"]]) ? $reclsPaymentsSum[$user["idRecl"]]["payedSum"] : 0);
            $users[$key]["unpayedTransactionsInfo"] = (isset($reclsUnpayedTransactionsInfo[$user["idRecl"]]) ? $reclsUnpayedTransactionsInfo[$user["idRecl"]]["commissionsSum"] : 0).' ('.(isset($reclsUnpayedTransactionsInfo[$user["idRecl"]]) ? $reclsUnpayedTransactionsInfo[$user["idRecl"]]["transactionsCount"] : 0).' транз.)';
            $users[$key]["balance"] =  $users[$key]["onAccount"] - (isset($reclsUnpayedTransactionsInfo[$user["idRecl"]]) ? $reclsUnpayedTransactionsInfo[$user["idRecl"]]["commissionsSum"] : 0);
        }

        $data = pagerDataPrepareDT($users, $pager);

        echo json_encode(array(
            'result' => 1,
            'data' => $data));
        exit;
    }

    /**
     * детальная информация об оффере, вкладки прокладок, лендингов, комиссий
     */
    public function expandreclAction()
    {
        $idCampaign = $this->getRequest()->getParam('id', '');

        $this->_view->assign('idCampaign', $idCampaign);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCampaign' => $idCampaign));
        exit;
    }

    /**
     * Отображение и обработка формы добавления рекла
     */
    public function addAction()
    {
        $ok = $this->getRequest()->getParam('ok', 0);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');

            $errors = array();
            if (!$this->_valid->validateForm($form))
            {
                $errors = $this->_valid->getInput()->getMessages();
            }

            if (!empty($errors))
            {
                echo json_encode(array('result' => 0, 'errors' => $errors));
                exit;
            }

            $idRecl = $this->_model->addRecl($form);
            echo json_encode(array('result' => 1));
            exit;
        }
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


    /**
     * Отображение и обработка формы редактирования рекла
     */
    public function editAction()
    {
        $cnf = Zend_Registry::get('cnf');

        $ok = $this->getRequest()->getParam('ok', 0);
        $idRecl = $this->getRequest()->getParam('id', 0);

        if (!$idRecl)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }



        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');

            $errors = array();
            if (!$this->_valid->validateForm($form))
            {
                $errors = $this->_valid->getInput()->getMessages();
            }

            if (!empty($errors))
            {
                echo json_encode(array('result' => 0, 'errors' => $errors));
                exit;
            }

            $this->_model->editRecl($idRecl, $form);

            echo json_encode(array('result' => 1));
            exit;
        }
        $recl = $this->_model->getRecl($idRecl);
        $this->_view->assign('recl', $recl);
        $this->_view->assign('idRecl', $idRecl);

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * жсон список реклов для автодополнения
     */
    public function reclslistAction()
    {
        $term = $this->getRequest()->getParam('term', '');
        $activeOnly = $this->getRequest()->getParam('activeOnly', 0);

        $list = $this->_model->getReclsList($term, $activeOnly);
        echo json_encode($list);
        exit;
    }

    /**
     * Операция изменения статуса рекла
     */
    public function changestatusAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $changeTo = $this->getRequest()->getParam('change_to', 0);

        if (!$id || !$changeTo)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }
        if ($this->_model->changeStatus($id, $changeTo))
        {
            echo json_encode(array('result' => 1));
            exit;
        }
        else
        {
            echo json_encode(array('result' => 0, 'errors' => "Ошибка изменения"));
            exit;
        }
    }

    /**
     * детальная информация о рекле, вкладки его кампаний, пользователей офиса, и настроек забора статусов
     */
    public function expandAction()
    {
        $idRecl = $this->getRequest()->getParam('id', '');

        $this->_view->assign('idRecl', $idRecl);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idRecl' => $idRecl));
        exit;
    }
}