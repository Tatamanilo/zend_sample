<?php

class Users_GroupsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('title', $this->_valid->getMessage('title'));
    }

    /**
     * список групп
     */
    public function indexAction()
    {
        $groups = $this->_model->getGroups();

        $this->_view->assign('groups', $groups);
        $this->_view->addView($this->_defaultTpl, $this->_mca);
    }


    /**
     * Отображение и обработка формы добавления группы
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

            $idUserGroup = $this->_model->addGroup($form);

            // получть массив пользователей которіе пітаемся добавить в группу
            $usersToAdd = explode(";", $form["idUsers"]);

            // установить айди группы для добавляемых пользователей (исключать те для которых группа уже установлена)
            $this->_model->setGroupForUsers($usersToAdd, $idUserGroup);

            // получить массив фактически добавленных юзеров (исключены те которые состояли в другой группе)
            $usersAdded = $this->_model->getGroupUsers($idUserGroup);

            // отредактировать запись группы, установить в ней новый список пользователей
            $this->_model->editGroup($idUserGroup, array("idUsers" => implode(";", $usersAdded)));

            if (!count($usersAdded))
            {
                echo json_encode(array('result' => 0, 'errors' => 'Группа не создана, так как выбранные пользователи были ранее добавлены в другую группу.'));
                exit;
            }
            if (count($usersToAdd) != count($usersAdded))
            {
                echo json_encode(array('result' => 1, 'mess' => 'Не все пользователи добавлены. Какой-то был добавлен ранее в другую группу'));
                exit;
            }
            echo json_encode(array('result' => 1));
            exit;
        }
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


    /**
     * Отображение и обработка формы редактирования группы
     */
    public function editAction()
    {
        $idUserGroup = $id = $this->getRequest()->getParam('id');
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

            // получить массив пользователей состоящих в группе до момента редактирования
            $usersInGroup = $this->_model->getGroupUsers($idUserGroup);

            // получть новый массив пользователей который пытаемся установить для группы
            $usersWantToBeInGroup = array();
            if (!empty($form["idUsers"]))
            {
                $usersWantToBeInGroup = explode(";", trim($form["idUsers"], ";"));
            }

            // получить массив пользователей, которых нужно убрать из группы
            $usersToDelete = array_diff($usersInGroup, $usersWantToBeInGroup);
            // получить массив пользователей, которых нужно добавить в группу
            $usersToAdd = array_diff($usersWantToBeInGroup, $usersInGroup);

            // сбросить в таблице юзеров айди группы для удаляемых из группы пользователей
            $this->_model->unsetGroupForUsers($usersToDelete, $idUserGroup);

            // установить айди группы для добавляемых пользователей (исключать те для которых группа уже установлена)
            // могут не добавиться те которые состоят в группе
            $this->_model->setGroupForUsers($usersToAdd, $idUserGroup);

            // получить массив фактически установленных для группы юзеров (исключены те которых пытались добавить но они состояли в другой группе)
            $usersNowInGroup = $this->_model->getGroupUsers($idUserGroup);

            // получить массив фактически удаленных пользователей
            $usersDeleted = array_diff($usersInGroup, $usersNowInGroup);
            // получить массив фактически добавленных пользователей
            $usersAdded = array_diff($usersNowInGroup, $usersInGroup);

            $this->_syncGroupUsersAddedAndDeleted($idUserGroup, $usersAdded, $usersDeleted);

            // установить новый список пользователей. он может отличаться от того который пытались установить на странице
            $form["idUsers"] = implode(";", $usersNowInGroup);

            $this->_model->editGroup($id, $form);
            echo json_encode(array('result' => 1));
            exit;
        }

        $group = $this->_model->getGroup($id);
        $users = $this->_model->getUsers(str_replace(";", ",", $group["idUsers"]));
        $this->_view->assign('group', $group);
        $this->_view->assign('users', $users);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * удаление группы
     */
    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        if (!$id)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустой ID"));
            exit;
        }
        if ($this->_model->deleteGroup($id))
        {
            echo json_encode(array('result' => 1));
            exit;
        }
        else
        {
            echo json_encode(array('result' => 0, 'errors' => "Ошибка удаления"));
            exit;
        }
    }

    /**
     * список пользователей в группе
     */
    public function showgroupusersAction()
    {
        $ids = $this->getRequest()->getParam('uids', '');

        $users = $this->_model->getUsers(str_replace(";", ",", $ids));
        $this->_view->assign('users', $users);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * Возвращает json список аффов для автодополнения при добавлении в группу за исключение тех кто состоит в других группах
     */
    public function affsearchlistAction()
    {
        $idRole = $this->_cnf->common->roles->affiliate;
        $term = urldecode($this->getRequest()->getParam('term', ''));

        $list = $this->_model->getFreeUsersList($idRole, $term);
        echo json_encode($list);
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
        $group = $this->_model->getGroup($id);
        $usersCount = !empty($group['idUsers']) ? count(explode(';', $group['idUsers'])) : 0;

        if ($usersCount > 0 && $changeTo == "D")
        {
            echo json_encode(array('result' => 0, 'errors' => "Группа не может быть деактивирована пока к ней прикреплены пользователи."));
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
     * Синхронизация удаления\добавления пользователя в группу по отношению к пользователям кампании, коммиссий
     */
    private function _syncGroupUsersAddedAndDeleted($idUserGroup, $usersAdded, $usersDeleted)
    {
        $campaigns = $this->_model->getCampaignsAssignedToGroup($idUserGroup);

        Zend_Loader::loadClass("AffsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
        $_affsModel = new AffsModel;

        foreach ($campaigns as $campaign)
        {
            if (!empty($usersDeleted))
            {
                $_affsModel->deleteUsersFromCampaign($campaign["idCampaign"], $usersDeleted, 0);
            }
            if (!empty($usersAdded))
            {
                $_affsModel->addUsersToCampaign($campaign["idCampaign"], $usersAdded);
            }
        }

        $commissionSections = $this->_model->getCommissionSectionsAssignedToGroup($idUserGroup);
        Zend_Loader::loadClass("CommissionsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
        $_commissionsModel = new CommissionsModel;

        foreach ($commissionSections as $commissionSection)
        {
            if (!empty($usersDeleted))
            {
                $_commissionsModel->deleteUsersFromCommissionSection($commissionSection["idCommissionSection"], $usersDeleted, 0);
            }
            if (!empty($usersAdded))
            {
                $_commissionsModel->addUsersToCommissionSection($commissionSection["idCommissionSection"], $commissionSection["idCampaign"], $usersAdded);
            }
            $_commissionsModel->syncSectionUsersToCommissions($commissionSection["idCommissionSection"]);
        }

    }
}