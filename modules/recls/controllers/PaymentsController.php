<?php

class Recls_PaymentsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('main_title', $this->_valid->getMessage('title'));
    }

    /**
     * отображение истории пополнений счета и формы пополнения счета
     */
    public function accountAction()
    {
        $cnf = Zend_Registry::get('cnf');
        $idRecl = $this->getRequest()->getParam('id', '');


        $this->_view->assign('idRecl', $idRecl);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idRecl' => $idRecl));
        exit;
    }

    /**
     * отображениt истории пополнений счета
     */
    public function accounthistoryAction()
    {
        $idRecl = $this->getRequest()->getParam('id', '');

        if (!$idRecl)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $pager = pagerPrepareDT($this->getRequest());
        $history = $this->_model->getAccountHistory($idRecl, $pager);
        $data = pagerDataPrepareDT($history, $pager);

        echo json_encode(array(
            'result' => 1,
            'data' => $data));
        exit;
    }

    /**
     * обработка пополнения счета
     */
    public function addaccountpaymentAction()
    {
        $idRecl = $this->getRequest()->getParam('idRecl', '');

        if (!$idRecl)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

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

        $this->_model->addAccountPayment($idRecl, $form);
        echo json_encode(array('result' => 1));
        exit;
    }


    /**
     * Операция изменения статуса коммиссии
     */
    public function changeaccountpaymentapproveAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $changeTo = $this->getRequest()->getParam('change_to', 0);

        if (!$id)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }
        if ($this->_model->changeAccountPaymentApprove($id, $changeTo))
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
     * отображение истории оплат транзакции по кампании и формы оплаты транзакций
     */
    public function paymentsAction()
    {
        $cnf = Zend_Registry::get('cnf');
        $idRecl = $this->getRequest()->getParam('idRecl', '');
        $idCampaign = $this->getRequest()->getParam('idCampaign', '');

        $onAccount = $this->_model->getReclOnAccount($idRecl);

        $this->_view->assign('idRecl', $idRecl);
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('onAccount', $onAccount);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idRecl' => $idRecl, 'idCampaign' => $idCampaign));
        exit;
    }

    /**
     * жсон данніе истории оплат транзакции
     */
    public function paymentshistoryAction()
    {
        $idRecl = $this->getRequest()->getParam('idRecl', '');
        $idCampaign = $this->getRequest()->getParam('idCampaign', '');

        if (!$idRecl || !$idCampaign)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $pager = pagerPrepareDT($this->getRequest());
        $history = $this->_model->getPaymentsHistory($idRecl, $idCampaign, $pager);
        $data = pagerDataPrepareDT($history, $pager);

        echo json_encode(array(
            'result' => 1,
            'data' => $data));
        exit;
    }

    /**
     * обработка оплат транзакций по кампании
     */
    public function addtransactionspaymentAction()
    {
        $idRecl = $this->getRequest()->getParam('idRecl', '');
        $idCampaign = $this->getRequest()->getParam('idCampaign', '');


        if (!$idRecl || !$idCampaign)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $onAccount = $this->_model->getReclOnAccount($idRecl);

        $form = $this->getRequest()->getParam('form');

        $errors = array();
        if (!$this->_valid->validateForm($form))
        {
            $errors = $this->_valid->getInput()->getMessages();
        }

        if ($onAccount < $form["paySum"])
        {
            $errors[] = 'Запрашиваемая сумма превышает доступную на счету ('.$onAccount.')';
        }

        if (!empty($errors))
        {
            echo json_encode(array('result' => 0, 'errors' => $errors));
            exit;
        }

        $transactions = $this->_model->getReclCampaignUnpayedTransactions($idRecl, $idCampaign);

        if (empty($transactions))
        {
            echo json_encode(array('result' => 0, 'errors' => "Все транзакции оплачены"));
            exit;
        }

        $total = 0;
        $idTransactions = array();
        $notEnough = false;
        foreach ($transactions as $transaction)
        {
            if (($total + $transaction["reclComission"]) > $form["paySum"])
            {
                $notEnough = true;
                break;
            }
            $total += $transaction["reclComission"];
            $idTransactions[] = $transaction["idTransaction"];

        }
        if (!empty($idTransactions))
        {
            $form["paySum"] = $total;
            $form["idTransactions"] = implode(";", $idTransactions);

            $idReclPayment = $this->_model->addTransactionPayment($idRecl, $idCampaign, $form);
            $this->_model->markTransactionsPayedByRecl($idTransactions, $idReclPayment);

            echo json_encode(array('result' => 1, 'mess' => 'Оплачено '.count($idTransactions).' транзакций на общую сумму '.$total));
            exit;
        }
        else
        {
            echo json_encode(array('result' => 0, 'errors' => "Недостаточная сумма для оплаты транзакции"));
            exit;
        }
    }
}