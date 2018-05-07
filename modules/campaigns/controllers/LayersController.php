<?php

class Campaigns_LayersController extends MyController
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

        $layers = $this->_model->getCampaignLayers($idCampaign);
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('layers', $layers);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCampaign' => $idCampaign));
        exit;
    }


    /**
     * блок и обработка добавления новой прокладки
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

            $this->_model->addLayer($idCampaign, $form);
            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('idCampaign', $idCampaign);

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * блок и обработка редактирования прокладки
     */
    public function editAction()
    {
        $idLayer = $this->getRequest()->getParam('id', 0);
        $ok = $this->getRequest()->getParam('ok', 0);

        if (!$idLayer)
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
            $this->_model->editLayer($idLayer, $form);
            echo json_encode(array('result' => 1));
            exit;
        }
        $layer = $this->_model->getLayer($idLayer);

        $this->_view->assign('idLayer', $idLayer);
        $this->_view->assign('layer', $layer);

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * Операция изменения статуса прокладки
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
     * Операция изменения типа приватности прокладки
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
     * список пользователей прокладки и блок присоединения новых пользователей к прокладке
     */
    public function usersAction()
    {
        $idLayer = $this->getRequest()->getParam('id', '');

        $users = $this->_model->getLayerUsers($idLayer);
        $layer = $this->_model->getLayer($idLayer);
        $idCampaign = $layer["idCampaign"];

        Zend_Loader::loadClass("CommissionsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
        $_commissionsModel = new CommissionsModel;
        $commissions = $_commissionsModel->getCampaignCommissions($idCampaign);

        Zend_Loader::loadClass("GroupsModel", $this->_cnf->path->modulesFront . "users" . $this->_cnf->path->models);
        $_groupsModel = new GroupsModel;
        $groups = $_groupsModel->getGroups();
        $this->_view->assign('idLayer', $idLayer);
        $this->_view->assign('users', $users);
        $this->_view->assign('commissions', $commissions);
        $this->_view->assign('listOnly', false);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idLayer' => $idLayer));
        exit;
    }

    /**
     * список пользователей прокладки
     */
    public function userslistAction()
    {
        $idLayer = $this->getRequest()->getParam('id', '');

        $users = $this->_model->getLayerUsers($idLayer);
        $this->_view->assign('idLayer', $idLayer);
        $this->_view->assign('users', $users);
        $this->_view->assign('listOnly', true);
        $html = $this->_view->fetch('LayersUsers.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idLayer' => $idLayer));
        exit;
    }

    /**
     * жсон список лендингов кампании
     */
    public function layerslistAction()
    {
        $idCampaign = $this->getRequest()->getParam('idCampaign', '');

        $items = $this->_model->getCampaignLayers($idCampaign);
        echo json_encode(array('result' => 1, 'items' => $items));
        exit;
    }

    /**
     * присоединение пользователя к прокладке
     */
    public function adduserAction()
    {
        $idLayer = $this->getRequest()->getParam('idLayer', 0);
        $idUser = $this->getRequest()->getParam('idUser', 0);

        if (!$idLayer || !$idUser)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $layer = $this->_model->getLayer($idLayer);
        $idCampaign = $layer["idCampaign"];

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
        $this->_model->addUserToLayer($idLayer, $idUser);
        echo json_encode(array('result' => 1));
        exit;
    }


    /**
     * присоединение группы пользователей коммиссии к прокладке
     */
    public function addusersgroupAction()
    {
        $idLayer = $this->getRequest()->getParam('idLayer', 0);
        $idCommission = $this->getRequest()->getParam('idCommission', 0);

        if (!$idLayer || !$idCommission)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        Zend_Loader::loadClass("CommissionsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
        $_commissionsModel = new CommissionsModel;
        $idUsers = $_commissionsModel->getCommissionUsersIds($idCommission);

        if (!empty($idUsers))
        {
            $this->_model->addUsersToLayer($idLayer, $idUsers);
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
     * удаление пользователя прокладки
     */
    public function deleteuserAction()
    {
        $idLayer = $this->getRequest()->getParam('idLayer', 0);
        $idUser = $this->getRequest()->getParam('idUser', 0);

        if (!$idLayer || !$idUser)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->deleteUserFromLayer($idLayer, $idUser);
        echo json_encode(array('result' => 1));
        exit;
    }

    /**
     * удаление пользователей прокладки
     */
    public function deleteusersAction()
    {
        $idLayer = $this->getRequest()->getParam('idLayer', 0);
        $idUsers = $this->getRequest()->getParam('usersToDelete', 0);

        if (!$idLayer || !$idUsers)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->deleteUsersFromLayer($idLayer, $idUsers);
        echo json_encode(array('result' => 1));
        exit;
    }

    /**
     * перерасчет EPC прокладки
     */
    public function recalcepcAction()
    {
        $idLayer = $this->getRequest()->getParam('idLayer', 0);

        if (!$idLayer)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $statsDataToday = $this->_model->getLayerStat($idLayer, "today");
        $statsDataYesterday = $this->_model->getLayerStat($idLayer, "yesterday");
        $statsDataWeek = $this->_model->getLayerStat($idLayer, "week");
        $statsDataAll = $this->_model->getLayerStat($idLayer);
        $this->_model->editLayer($idLayer, array(
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