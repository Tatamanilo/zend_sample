<?php

class Campaigns_ReclsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('title', $this->_valid->getMessage('title'));
    }

    /**
     * спислк прокладок
     */
    public function indexAction()
    {
        $idCampaign = $this->getRequest()->getParam('id', '');

        $recls = $this->_model->getCampaignRecls($idCampaign);
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('recls', $recls);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCampaign' => $idCampaign));
        exit;
    }

    /**
     * блок добавления нового рекламодателя и количества допустимых заявок в день для этого рекламодателя\кампании
     */
    public function addAction()
    {
        $idCampaign = $this->getRequest()->getParam('id', 0);
        $ok = $this->getRequest()->getParam('ok', 0);

        if (!$idCampaign)
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

            if ($this->_model->getReclCampaign($idCampaign, $form["idRecl"]))
            {
                $errors["recl_exists"] = 'Рекламодатель уже добавлен';
            }

            if (!empty($errors))
            {
                echo json_encode(array('result' => 0, 'errors' => $errors));
                exit;
            }

            $idReclCampaign = $this->_model->addReclCampaign($idCampaign, $form["idRecl"], $form["transCountPerDay"]);

            if ($idReclCampaign)
            {
                $this->_model->resetCampaignReclsCoeffs($idCampaign);
            }

            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('idCampaign', $idCampaign);

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * редактирование количества допустимых заявок в день
     */
    public function edittranscountAction()
    {
        $idReclCampaign = $this->getRequest()->getParam('rcid');
        $value = $this->getRequest()->getParam('value');

        if (!$idReclCampaign)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->editTransCount($idReclCampaign, $value);
        $reclCampaign = $this->_model->getReclCampaignById($idReclCampaign);
        $this->_model->resetCampaignReclsCoeffs($reclCampaign["idCampaign"]);

        echo json_encode(array('result' => 1, 'value' => $value));
        //echo $value;
        exit;
    }

    /**
     * жсон список реклов для автодополнения
     */
    public function reclslistAction()
    {
        $term = $this->getRequest()->getParam('term', '');

        $list = $this->_model->getReclsList($term);
        echo json_encode($list);
        exit;
    }


    /**
     * блок присоединения рекламодателя
     */
    public function assignAction()
    {
        $id = $this->getRequest()->getParam('id');

        $recls = $this->_model->getCampaignRecls($id);
        $reclsIds = $this->_model->getCampaignReclsIds($id);
        $this->_view->assign('recls', $recls);
        $this->_view->assign('reclsIds', implode(";",$reclsIds));
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


}