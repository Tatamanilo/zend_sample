<?php

class Campaigns_CommissionsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('title', $this->_valid->getMessage('title'));
    }

    /**
     * список комиссий
     */
    public function indexAction()
    {
        $idCampaign = $this->getRequest()->getParam('id', '');

        $commissions = $this->_model->getCampaignCommissions($idCampaign);
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('commissions', $commissions);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCampaign' => $idCampaign));
        exit;
    }

    /**
     * список пользователей коммиссии и блок присоединения новых пользователей к коммиссии
     */
    public function usersAction()
    {
        $idCommissionSection = $this->getRequest()->getParam('id', '');

        $users = $this->_model->getCommissionSectionUsers($idCommissionSection, 1);
        $csecgroups = $this->_model->getCommissionSectionUserGroups($idCommissionSection);
        // get all groups
        $groups = $this->_model->getGroups();
        $this->_view->assign('idCommissionSection', $idCommissionSection);
        $this->_view->assign('users', $users);
        $this->_view->assign('csecgroups', $csecgroups);
        $this->_view->assign('groups', $groups);
        $this->_view->assign('listOnly', false);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCommissionSection' => $idCommissionSection));
        exit;
    }

    /**
     * список пользователей коммиссии
     */
    public function userslistAction()
    {
        $idCommissionSection = $this->getRequest()->getParam('id', '');

        $users = $this->_model->getCommissionSectionUsers($idCommissionSection, 1);
        $csecgroups = $this->_model->getCommissionSectionUserGroups($idCommissionSection);
        $this->_view->assign('idCommissionSection', $idCommissionSection);
        $this->_view->assign('users', $users);
        $this->_view->assign('csecgroups', $csecgroups);
        $this->_view->assign('listOnly', true);
        $html = $this->_view->fetch('CommissionsUsers.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCommissionSection' => $idCommissionSection));
        exit;
    }

    /**
     * гео информация, комиссии, страны, цены
     */
    public function geoinfoAction()
    {
        $idCampaign = $this->getRequest()->getParam('id', '');

        $commissions = $this->_model->getCampaignCommissionsGeo($idCampaign);
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('commissions', $commissions);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCampaign' => $idCampaign));
        exit;
    }

    /**
     * Операция изменения статуса коммиссии
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
     * Операция изменения типа подтверждения коммиссии
     */
    public function changeapprovetypeAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $changeTo = $this->getRequest()->getParam('change_to', 0);

        if (!$id || !$changeTo)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }
        if ($this->_model->changeApproveType($id, $changeTo))
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
     * блок и обработка добавления новой коммиссии
     */
    public function addAction()
    {
        $idCommissionSection = $this->getRequest()->getParam('idCommissionSection', 0);
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
            $form["idCommissionSection"] = $idCommissionSection;

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

            if (!isset($form["isGroupCommission"]))
            {
                $form["isGroupCommission"] = 0;
            }

            if ($form["countries"])
            {
                $formCountries = implode(";", $form["countries"]);
                unset($form["countries"]);
                $form["countries"] = $formCountries;
            }

            // edit countries and targets of campaign
            Zend_Loader::loadClass("CampaignsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
            $_campaignsModel = new CampaignsModel;
            $_campaignsModel->updateCampaignAggregateFields($idCampaign);

            $this->_model->addCommission($idCampaign, $form);
            echo json_encode(array('result' => 1, 'idCommissionSection' => $idCommissionSection));
            exit;
        }
        $targets = $this->_model->getTargets();
        $recls = $this->_model->getCampaignRecls($idCampaign);
        $commissionSection = $this->_model->getCommissionSection($idCommissionSection);

//        var_dump($commissionSection);
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('idCommissionSection', $idCommissionSection);
        $this->_view->assign('commissionSection', $commissionSection);
        $this->_view->assign('targets', $targets);
        $this->_view->assign('recls', $recls);
        $this->_view->assign('countries', $this->_model->getCountries());

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * блок и обработка редактирования коммиссии
     */
    public function editAction()
    {
        $idCommission = $this->getRequest()->getParam('id', 0);
        $ok = $this->getRequest()->getParam('ok', 0);

        if (!$idCommission)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $commission = $this->_model->getCommission($idCommission);

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

            if ($form["countries"])
            {
                $formCountries = implode(";", $form["countries"]);
                unset($form["countries"]);
                $form["countries"] = $formCountries;
            }

            // edit countries and targets of campaign
            Zend_Loader::loadClass("CampaignsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
            $_campaignsModel = new CampaignsModel;
            $_campaignsModel->updateCampaignAggregateFields($commission["idCampaign"]);

            $this->_model->editCommission($idCommission, $form);
            echo json_encode(array('result' => 1));
            exit;
        }
        $targets = $this->_model->getTargets();

        $recls = $this->_model->getCampaignRecls($commission["idCampaign"]);

        $commissionCountries = array();
        foreach (explode(";", $commission["countries"]) as $country)
        {
            $commissionCountries[$country] = $country;
        }

        $this->_view->assign('idCommission', $idCommission);
        $this->_view->assign('commission', $commission);
        $this->_view->assign('targets', $targets);
        $this->_view->assign('recls', $recls);
        $this->_view->assign('commissionCountries', $commissionCountries);
        $this->_view->assign('countries', $this->_model->getCountries());

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


    /**
     * присоединение пользователя к коммиссии
     */
    public function adduserAction()
    {
        $idCommissionSection = $this->getRequest()->getParam('idCommissionSection', 0);
        $idUser = $this->getRequest()->getParam('idUser', 0);

        if (!$idCommissionSection || !$idUser)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $commissionS = $this->_model->getCommissionSection($idCommissionSection);
        $idCampaign = $commissionS["idCampaign"];

        /*
        // if campaign users are related with commission users
        Zend_Loader::loadClass("CampaignsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
        $_campaignsModel = new CampaignsModel;

        if ($_campaignsModel->isCampaignPrivate($idCampaign))
        {
            Zend_Loader::loadClass("AffsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
            $_affsModel = new AffsModel;
            if (!$_affsModel->checkPrivateCampaignUser($idCampaign, $idUser))
            {
                echo json_encode(array('result' => 0, 'errors' => "Пользователь не состоит в данной приватной кампании"));
                exit;
            }
        }
        */
        if ($this->_model->addUserToCommissionSection($idCommissionSection, $idCampaign, $idUser))
        {
            $this->_model->syncSectionUsersToCommissions($idCommissionSection);
            echo json_encode(array('result' => 1));
        }
        else
        {
            echo json_encode(array('result' => 1, "mess" => "Пользователь уже состоит в какой-то связке этой кампании"));
        }
        exit;
    }


    /**
     * присоединение группы пользователей к коммиссии
     */
    public function addusersgroupAction()
    {
        $idCommissionSection = $this->getRequest()->getParam('idCommissionSection', 0);
        $idUserGroup = $this->getRequest()->getParam('idUserGroup', 0);

        if (!$idCommissionSection || !$idUserGroup)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $commissionS = $this->_model->getCommissionSection($idCommissionSection);
        $idCampaign = $commissionS["idCampaign"];

        $group = $this->_model->getGroup($idUserGroup);
        $idUsers = explode(";", $group["idUsers"]);

        if ($this->_model->addGroupToCommissionSection($idCommissionSection, $idCampaign, $idUserGroup))
        {
            $this->_model->addUsersToCommissionSection($idCommissionSection, $idCampaign, $idUsers);
            $this->_model->syncSectionUsersToCommissions($idCommissionSection);
            echo json_encode(array('result' => 1));
        }
        else
        {
            echo json_encode(array('result' => 1, "mess" => "Группа уже состоит в какой-то связке этой кампании"));
        }
        exit;
    }

    /**
     * удаление пользователя коммиссии
     */
    public function deleteuserAction()
    {
        $idCommissionSection = $this->getRequest()->getParam('idCommissionSection', 0);
        $idUser = $this->getRequest()->getParam('idUser', 0);

        if (!$idCommissionSection || !$idUser)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->deleteUserFromCommissionSection($idCommissionSection, $idUser);
        $this->_model->syncSectionUsersToCommissions($idCommissionSection);
        echo json_encode(array('result' => 1));
        exit;
    }

    /**
     * удаление пользователей коммиссии
     */
    public function deleteusersAction()
    {
        $idCommissionSection = $this->getRequest()->getParam('idCommissionSection', 0);
        $idUsers = $this->getRequest()->getParam('usersToDelete', 0);

        if (!$idCommissionSection || !$idUsers)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->deleteUsersFromCommissionSection($idCommissionSection, $idUsers);
        $this->_model->syncSectionUsersToCommissions($idCommissionSection); 
        echo json_encode(array('result' => 1));
        exit;
    }


    /**
     * список цен коммиссии и блок добавления новой цены
     */
    public function pricesAction()
    {
        $idCommission = $this->getRequest()->getParam('id', '');

        $prices = $this->_model->getCommissionPrices($idCommission);
        $this->_view->assign('idCommission', $idCommission);
        $this->_view->assign('prices', $prices);
        $this->_view->assign('listOnly', false);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCommission' => $idCommission));
        exit;
    }

    /**
     * список цен коммиссии
     */
    public function priceslistAction()
    {
        $idCommission = $this->getRequest()->getParam('id', '');

        $prices = $this->_model->getCommissionPrices($idCommission);
        $this->_view->assign('idCommission', $idCommission);
        $this->_view->assign('prices', $prices);
        $this->_view->assign('listOnly', true);
        $html = $this->_view->fetch('CommissionsPrices.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCommission' => $idCommission));
        exit;
    }


    /**
     * обработка добавления новой цены коммиссии
     */
    public function addpriceAction()
    {
        $idCommission = $this->getRequest()->getParam('idCommission', '');

        if (!$idCommission)
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

        $this->_model->addCommissionPrice($idCommission, $form);
        echo json_encode(array('result' => 1));
        exit;

    }

    /**
     * обработка редактирование поля цены коммисиии
     */
    public function editpricefieldAction()
    {
        $idCommissionPrice = $this->getRequest()->getParam('idCommissionPrice', '');
        $field = $this->getRequest()->getParam('fname', '');
        $value = $this->getRequest()->getParam('value', '');

        if (!$idCommissionPrice || !$field || !$value)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }
        $form[$field] = $value;

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

        $this->_model->editCommissionPrice($idCommissionPrice, $form);
        echo json_encode(array('result' => 1, 'value' => $value));
        exit;
    }

    /**
     * клон связки коммиссии
     */
    public function clonecommissionsectionAction()
    {
        $idCampaign = $this->getRequest()->getParam('idCampaign', '');
        $idCommissionSection = $this->getRequest()->getParam('idCommissionSection', '');

        if (!$idCampaign || !$idCommissionSection)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }
        $idCommissionSectionNew = $this->_model->cloneSection($idCommissionSection);

        echo json_encode(array('result' => 1, 'idCommissionSectionNew' => $idCommissionSectionNew));
        exit;
    }


    /**
     * редактирование названия связки коммиссий
     */
    public function editsectionnameAction()
    {
        $idCommissionSection = $this->getRequest()->getParam('idCommissionSection');
        $value = $this->getRequest()->getParam('value');

        if (!$idCommissionSection || !$value)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->editSectionName($idCommissionSection, $value);

        echo json_encode(array('result' => 1, 'value' => $value));
        exit;
    }
}