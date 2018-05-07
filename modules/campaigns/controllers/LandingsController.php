<?php

class Campaigns_LandingsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('title', $this->_valid->getMessage('title'));
    }

    /**
     * список лендингов
     */
    public function indexAction()
    {
        $idCampaign = $this->getRequest()->getParam('id', '');

        $landings = $this->_model->getCampaignLandings($idCampaign);
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('landings', $landings);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCampaign' => $idCampaign));
        exit;
    }

    /**
     * блок и обработка добавления нового лендинга
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

            if (!empty($errors))
            {
                echo json_encode(array('result' => 0, 'errors' => $errors));
                exit;
            }

            if (!isset($form["forPrivate"]))
            {
                $form["forPrivate"] = 0;
            }

            $this->_model->addLanding($idCampaign, $form);
            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('idCampaign', $idCampaign);

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * блок и обработка редактирования лендинга
     */
    public function editAction()
    {
        $idLanding = $this->getRequest()->getParam('id', 0);
        $ok = $this->getRequest()->getParam('ok', 0);

        if (!$idLanding)
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

            if (!isset($form["forPrivate"]))
            {
                $form["forPrivate"] = 0;
            }
            $this->_model->editLanding($idLanding, $form);
            echo json_encode(array('result' => 1));
            exit;
        }
        $landing = $this->_model->getLanding($idLanding);

        $this->_view->assign('idLanding', $idLanding);
        $this->_view->assign('landing', $landing);

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


    /**
     * Операция изменения статуса лендинга
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
     * Операция изменения типа приватности лендинга
     */
    public function changeforprivateAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $changeTo = $this->getRequest()->getParam('change_to', false);

        if (!$id || $changeTo === false)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }
        if ($this->_model->changeForPrivate($id, $changeTo))
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
     * список пользователей лендинга и блок присоединения новых пользователей к лендингу
     */
    public function usersAction()
    {
        $idLanding = $this->getRequest()->getParam('id', '');

        $users = $this->_model->getLandingUsers($idLanding);
        $landing = $this->_model->getLanding($idLanding);
        $idCampaign = $landing["idCampaign"];

        Zend_Loader::loadClass("CommissionsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
        $_commissionsModel = new CommissionsModel;
        $commissions = $_commissionsModel->getCampaignCommissions($idCampaign);

        Zend_Loader::loadClass("GroupsModel", $this->_cnf->path->modulesFront . "users" . $this->_cnf->path->models);
        $_groupsModel = new GroupsModel;
        $groups = $_groupsModel->getGroups();
        $this->_view->assign('idLanding', $idLanding);
        $this->_view->assign('users', $users);
        $this->_view->assign('commissions', $commissions);
        $this->_view->assign('listOnly', false);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idLanding' => $idLanding));
        exit;
    }

    /**
     * список пользователей лендинга
     */
    public function userslistAction()
    {
        $idLanding = $this->getRequest()->getParam('id', '');

        $users = $this->_model->getLandingUsers($idLanding);
        $this->_view->assign('idLanding', $idLanding);
        $this->_view->assign('users', $users);
        $this->_view->assign('listOnly', true);
        $html = $this->_view->fetch('LandingsUsers.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idLanding' => $idLanding));
        exit;
    }

    /**
     * жсон список лендингов кампании
     */
    public function landingslistAction()
    {
        $idCampaign = $this->getRequest()->getParam('idCampaign', '');

        $items = $this->_model->getCampaignLandings($idCampaign);
        echo json_encode(array('result' => 1, 'items' => $items));
        exit;
    }

    /**
     * присоединение пользователя к лендингу
     */
    public function adduserAction()
    {
        $idLanding = $this->getRequest()->getParam('idLanding', 0);
        $idUser = $this->getRequest()->getParam('idUser', 0);

        if (!$idLanding || !$idUser)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $landing = $this->_model->getLanding($idLanding);
        $idCampaign = $landing["idCampaign"];

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
        $this->_model->addUserToLanding($idLanding, $idUser);
        echo json_encode(array('result' => 1));
        exit;
    }


    /**
     * присоединение группы пользователей коммиссии к лендингу
     */
    public function addusersgroupAction()
    {
        $idLanding = $this->getRequest()->getParam('idLanding', 0);
        $idCommission = $this->getRequest()->getParam('idCommission', 0);

        if (!$idLanding || !$idCommission)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        Zend_Loader::loadClass("CommissionsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
        $_commissionsModel = new CommissionsModel;
        $idUsers = $_commissionsModel->getCommissionUsersIds($idCommission);

        if (!empty($idUsers))
        {
            $this->_model->addUsersToLanding($idLanding, $idUsers);
            echo json_encode(array('result' => 1));
            exit;
        }
        else
        {
            echo json_encode(array('result' => 0, 'errors' => "Нет пользователей в выбранной коммиссии"));
            exit;
        }

    }

    /**
     * удаление пользователя лендинга
     */
    public function deleteuserAction()
    {
        $idLanding = $this->getRequest()->getParam('idLanding', 0);
        $idUser = $this->getRequest()->getParam('idUser', 0);

        if (!$idLanding || !$idUser)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->deleteUserFromLanding($idLanding, $idUser);
        echo json_encode(array('result' => 1));
        exit;
    }

    /**
     * удаление пользователей лендинга
     */
    public function deleteusersAction()
    {
        $idLanding = $this->getRequest()->getParam('idLanding', 0);
        $idUsers = $this->getRequest()->getParam('usersToDelete', 0);

        if (!$idLanding || !$idUsers)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->deleteUsersFromLanding($idLanding, $idUsers);
        echo json_encode(array('result' => 1));
        exit;
    }


    /**
     * перерасчет EPC лендинга
     */
    public function recalcepcAction()
    {
        $idLanding = $this->getRequest()->getParam('idLanding', 0);

        if (!$idLanding)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $statsDataToday = $this->_model->getLandingStat($idLanding, "today");
        $statsDataYesterday = $this->_model->getLandingStat($idLanding, "yesterday");
        $statsDataWeek = $this->_model->getLandingStat($idLanding, "week");
        $statsDataAll = $this->_model->getLandingStat($idLanding);
        $this->_model->editLanding($idLanding, array(
            'epcToday' => number_format($statsDataToday["epc"], 2),
            'epcYesterday' => number_format($statsDataYesterday["epc"], 2),
            'epcWeek' => number_format($statsDataWeek["epc"], 2),
            'epcAll' => number_format($statsDataAll["epc"], 2),
            'epcDateEval' => date("Y-m-d H:i:s")
        ));
        echo json_encode(array(
            'result' => 1,
            'epcToday' => number_format($statsDataToday["epc"], 2),
            'epcYesterday' => number_format($statsDataYesterday["epc"], 2),
            'epcWeek' => number_format($statsDataWeek["epc"], 2),
            'epcAll' => number_format($statsDataAll["epc"], 2),
            'epcDateEval' => date("Y-m-d H:i:s")
        ));
        exit;
    }
}