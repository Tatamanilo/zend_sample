<?php

class Campaigns_AffsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('title', $this->_valid->getMessage('title'));
    }


    /**
     * список пользователей (аффов) кампании и блок присоединения новых пользователей к кампании
     */
    public function usersAction()
    {
        $idCampaign = $this->getRequest()->getParam('id', '');

        $users = $this->_model->getCampaignUsers($idCampaign, 1);
        $cgroups = $this->_model->getCampaignUserGroups($idCampaign);
        //Zend_Loader::loadClass("GroupsModel", $this->_cnf->path->modulesFront . "users" . $this->_cnf->path->models);
        //$_groupsModel = new GroupsModel;
        $groups = $this->_model->getGroups();
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('users', $users);
        $this->_view->assign('cgroups', $cgroups);
        $this->_view->assign('groups', $groups);
        $this->_view->assign('listOnly', false);
        $html = $this->_view->fetch('CampaignsUsers.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCampaign' => $idCampaign));
        exit;
    }

    /**
     * список пользователей кампании
     */
    public function userslistAction()
    {
        $idCampaign = $this->getRequest()->getParam('id', '');

        $users = $this->_model->getCampaignUsers($idCampaign, 1);
        $cgroups = $this->_model->getCampaignUserGroups($idCampaign);
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('users', $users);
        $this->_view->assign('cgroups', $cgroups);
        $this->_view->assign('listOnly', true);
        $html = $this->_view->fetch('CampaignsUsers.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCampaign' => $idCampaign));
        exit;
    }

    /**
     * присоединение пользователя к кампании
     */
    public function adduserAction()
    {
        $idCampaign = $this->getRequest()->getParam('idCampaign', 0);
        $idUser = $this->getRequest()->getParam('idUser', 0);

        if (!$idCampaign || !$idUser)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->addUserToCampaign($idCampaign, $idUser);
        echo json_encode(array('result' => 1));
        exit;
    }


    /**
     * присоединение группы пользователей к кампании
     */
    public function addusersgroupAction()
    {
        $idCampaign = $this->getRequest()->getParam('idCampaign', 0);
        $idUserGroup = $this->getRequest()->getParam('idUserGroup', 0);

        if (!$idCampaign || !$idUserGroup)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        //Zend_Loader::loadClass("GroupsModel", $this->_cnf->path->modulesFront . "users" . $this->_cnf->path->models);
        //$_groupsModel = new GroupsModel;
        $group = $this->_model->getGroup($idUserGroup);


        $idUsers = !empty($group["idUsers"]) ? explode(";", $group["idUsers"]) : array();

        $this->_model->addGroupToCampaign($idCampaign, $idUserGroup);
        $this->_model->addUsersToCampaign($idCampaign, $idUsers);
        echo json_encode(array('result' => 1));
        exit;
    }

    /**
     * удаление пользователя кампании
     */
    public function deleteuserAction()
    {
        $idCampaign = $this->getRequest()->getParam('idCampaign', 0);
        $idUser = $this->getRequest()->getParam('idUser', 0);

        if (!$idCampaign || !$idUser)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->deleteUserFromCampaign($idCampaign, $idUser);
        echo json_encode(array('result' => 1));
        exit;
    }

    /**
     * удаление пользователей кампании
     */
    public function deleteusersAction()
    {
        $idCampaign = $this->getRequest()->getParam('idCampaign', 0);
        $idUsers = $this->getRequest()->getParam('usersToDelete', 0);

        if (!$idCampaign || !$idUsers)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $this->_model->deleteUsersFromCampaign($idCampaign, $idUsers);
        echo json_encode(array('result' => 1));
        exit;
    }


    /**
     * удаление группы кампании
     */
    public function deletegroupAction()
    {
        $idCampaign = $this->getRequest()->getParam('idCampaign', 0);
        $idUserGroup = $this->getRequest()->getParam('idUserGroup', 0);

        if (!$idCampaign || !$idUserGroup)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $group = $this->_model->getGroup($idUserGroup);
        // пользователи удаляемой группы
        $groupToDeleteUserIds = explode(";", $group["idUsers"]);

        // чтобы не удалить пользователей которые состоят и в других группах прикрепленных к кампании нужно исключить их
        // получить айдишники прикрепленных групп к кампании
        $groupIds = $this->_model->getCampaignUserGroupsIds($idCampaign);

        // исключить из групп кампании ту, которыю собираемся удалять, чтобы получить всех пользователей остальных групп
        $groupIds = array_diff($groupIds, array($idUserGroup));

        // получть айдишники пользователей групп кампании (кроме той которую удаляем)
        $userIdsArr = $this->_model->getUserIdsByGroupIds($groupIds);

        // перебираем строки айдишников пользователей групп кампании (кроме той которую удаляем)
        foreach ($userIdsArr as $userIdsStr)
        {
            // айдишники пользователей которые состоят в кампании группы (кроме той которую удаляем)
            $userIds = explode(";", $userIdsStr);
            // исключаем из массива пользователей которые надо удалить из кампании тех, которые состоят еще в какойто группе этой кампании
            $groupToDeleteUserIds = array_diff($groupToDeleteUserIds, $userIds);
        }
        // отсоединение (удаление) группы от кампании
        $this->_model->deleteGroupFromCampaign($idCampaign, $idUserGroup);

        // отсоединение пользователей удаляемой группы от кампании (за исключение пользователей которые состоят еще в каких-то группах этой капмнаии)
        $this->_model->deleteUsersFromCampaign($idCampaign, $groupToDeleteUserIds);

        if (empty($groupToDeleteUserIds))
        {
            echo json_encode(array('result' => 1, "mess" => "Группа отсоединена успешно, но ни один пользователь группы не отсоединен от кампании, так как состоит в других группах этой кампании"));
        }
        else
        {
            echo json_encode(array('result' => 1));
        }
        exit;
    }
}