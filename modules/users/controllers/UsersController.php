<?php

class Users_UsersController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('title', $this->_valid->getMessage('title'));
    }

    /**
     * отображение списка пользователей
     */
    public function indexAction()
    {
        $idRole = $this->getRequest()->getParam('rid', 1);

        if ($idRole == 1)
            $this->_view->assign('main_title', $this->_valid->getMessage("adminTitle"));
        if ($idRole == 6)
            $this->_view->assign('main_title', $this->_valid->getMessage("supportTitle"));
        if ($idRole == 7)
            $this->_view->assign('main_title', $this->_valid->getMessage("managerTitle"));
        if ($idRole == 3)
            $this->_view->assign('main_title', $this->_valid->getMessage("affiliateTitle"));
        if ($idRole == 4)
            $this->_view->assign('main_title', $this->_valid->getMessage("merchantTitle").' <small>'.$this->_valid->getMessage("merchantSubTitle").'</small>');

        $this->_view->assign('idRole', $idRole);
        $this->_view->addView($this->_defaultTpl, $this->_mca);
    }

    /**
     * Возвращает json список пользователей
     */
    public function userlistAction()
    {
        $idRole = $this->getRequest()->getParam('id', 1);
        $pager = pagerPrepareDT($this->getRequest());
        $form = $this->getRequest()->getParam('form', 0);
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
        }

        switch($idRole)
        {
            case 1:
            case 6:
            case 7:
                $fields = array('userRef' => 'userRef', 'login' => 'login', 'status' => 'status');
                $users = $this->_model->getUsersByRole($idRole, $pager, $fields, $form);
                $data = pagerDataPrepareDT($users, $pager);
                break;
            case 3:
                $fields = array(
                    'idUser' => 'id',
                    'login' => 'login',
                    'name' => 'name',
                    'additionalInfo' => 'additionalInfo',
                    'status' => 'status',
                    '', ''
                );
                $users = $this->_model->getUsersByRole($idRole, $pager, $fields, $form);
                $data = pagerDataPrepareDT($users, $pager);
                break;
            case 4:
                $fields = array(
                    'idUser' => 'id',
                    'login' => 'login',
                    'name' => 'name',
                    'additionalInfo' => 'additionalInfo',
                    'status' => 'status',
                    '', ''
                );
                $users = $this->_model->getUsersByRole($idRole, $pager, $fields, $form);
                $data = pagerDataPrepareDT($users, $pager);
                break;
        }
        echo json_encode(array(
            'result' => 1,
            'data' => $data));
        exit;
    }


    /**
     * Возвращает json список аффов для автодополнения
     */
    public function affsearchlistAction()
    {
        $idRole = $this->_cnf->common->roles->affiliate;
        $term = urldecode($this->getRequest()->getParam('term', ''));
        $activeOnly = $this->getRequest()->getParam('activeOnly', 1);

        $list = $this->_model->getUsersList($idRole, $term, $activeOnly);

        echo json_encode($list);
        exit;
    }

    /**
     * Форма добавления нового пользователя
     */
    public function addAction()
    {
        $idRole = $this->getRequest()->getParam('rid', 0);
        switch ($idRole)
        {
            case $this->_cnf->common->roles->admin:
                $this->forward('addadmin', 'users', 'users');
                return;
            case $this->_cnf->common->roles->support:
                $this->forward('addsupport', 'users', 'users');
                return;
            case $this->_cnf->common->roles->manager:
                $this->forward('addmanager', 'users', 'users');
                return;
            case $this->_cnf->common->roles->affiliate:
                $this->forward('addaffiliate', 'users', 'users');
                return;
            case $this->_cnf->common->roles->merchant:
                $this->forward('addmerchant', 'users', 'users');
                return;
        }
        list(, $module, $controller, $action) = explode('/', $this->_cnf->common->defBpPage);
        $this->forward($action, $controller, $module, true);
    }

    /**
     * Форма редактирования пользователя
     */
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $user = $this->_model->getUserById($id);
        if (!empty($user))
        {
            switch ($user['idRole'])
            {
                case $this->_cnf->common->roles->admin:
                    $this->forward('editadmin', 'users', 'users', false, null, array('user' => $user));
                    return;
                case $this->_cnf->common->roles->support:
                    $this->forward('editsupport', 'users', 'users', false, null, array('user' => $user));
                    return;
                case $this->_cnf->common->roles->manager:
                    $this->forward('editmanager', 'users', 'users', false, null, array('user' => $user));
                    return;
                case $this->_cnf->common->roles->affiliate:
                    $this->forward('editaffiliate', 'users', 'users', false, null, array('user' => $user));
                    return;
                case $this->_cnf->common->roles->merchant:
                    $this->forward('editmerchant', 'users', 'users', false, null, array('user' => $user));
                    return;
            }
        }

        list(, $module, $controller, $action) = explode('/', $this->_cnf->common->defBpPage);
        $this->forward($action, $controller, $module, true);
    }

    /**
     * Операция удаления пользователя
     */
    public function deleteAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        if (!$id)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустой ID"));
            exit;
        }
        if ($this->_model->setDeleteStatus($id))
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
     * Операция изменения статуса пользователя
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
     * Операция изменения статуса юзера проверен\непроверен пользователя
     */
    public function changecheckedAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $changeTo = $this->getRequest()->getParam('change_to', 0);
        if (!$id || ($changeTo === false))
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }
        if ($this->_model->changeChecked($id, $changeTo))
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
     * Операция изменения заморозки\разморозки пользователя
     */
    public function changefreezeAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $changeTo = $this->getRequest()->getParam('change_to', false);
        if (!$id || ($changeTo === false))
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }
        if ($this->_model->changeFreeze($id, $changeTo))
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
     * Отображение и Обработка формы добавления администратора
     */
    public function addadminAction()
    {
        $ok = $this->getRequest()->getParam('ok', 0);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                echo json_encode(array('result' => 0, 'errors' => $this->_valid->getInput()->getMessages()));
                exit;
            }
            $form['role'] = $this->_cnf->common->roles->admin;
            $this->_model->addGroup($form);
            echo json_encode(array('result' => 1));
            exit;
        }
        $html = $this->_view->fetch('UsersAddAdmin.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * Отображение и Обработка формы редактирования администратора
     */
    public function editadminAction()
    {
        $id = $this->getRequest()->getParam('id');
        $user = $this->getRequest()->getParam('user');
        $ok = $this->getRequest()->getParam('ok', 0);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                echo json_encode(array('result' => 0, 'errors' => $this->_valid->getInput()->getMessages()));
                exit;
            }
            $this->_model->editUser($form, $id);
            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('user', $user);
        $html = $this->_view->fetch('UsersEditAdmin.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * Отображение и Обработка формы добавления саппорта
     */
    public function addsupportAction()
    {
        $ok = $this->getRequest()->getParam('ok', 0);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                echo json_encode(array('result' => 0, 'errors' => $this->_valid->getInput()->getMessages()));
                exit;
            }
            $form['role'] = $this->_cnf->common->roles->support;
            $this->_model->addUser($form);
            echo json_encode(array('result' => 1));
            exit;
        }
        $html = $this->_view->fetch('UsersAddSupport.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * Отображение и Обработка формы редактирования саппорта
     */
    public function editsupportAction()
    {
        $id = $this->getRequest()->getParam('id');
        $user = $this->getRequest()->getParam('user');
        $ok = $this->getRequest()->getParam('ok', 0);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                echo json_encode(array('result' => 0, 'errors' => $this->_valid->getInput()->getMessages()));
                exit;
            }
            $this->_model->editUser($form, $id);
            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('user', $user);
        $html = $this->_view->fetch('UsersEditSupport.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


    /**
     * Отображение и Обработка формы добавления менеджера
     */
    public function addmanagerAction()
    {
        $ok = $this->getRequest()->getParam('ok', 0);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                echo json_encode(array('result' => 0, 'errors' => $this->_valid->getInput()->getMessages()));
                exit;
            }
            $form['role'] = $this->_cnf->common->roles->manager;
            $this->_model->addUser($form);
            echo json_encode(array('result' => 1));
            exit;
        }
        $html = $this->_view->fetch('UsersAddManager.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * Отображение и Обработка формы редактирования менеджера
     */
    public function editmanagerAction()
    {
        $id = $this->getRequest()->getParam('id');
        $user = $this->getRequest()->getParam('user');
        $ok = $this->getRequest()->getParam('ok', 0);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                echo json_encode(array('result' => 0, 'errors' => $this->_valid->getInput()->getMessages()));
                exit;
            }
            $this->_model->editUser($form, $id);
            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('user', $user);
        $html = $this->_view->fetch('UsersEditManager.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


    /**
     * Редактирование аффилиейта
     */
    public function editaffiliateAction()
    {
        $id = $this->getRequest()->getParam('id');
        $user = $this->getRequest()->getParam('user');
        $ok = $this->getRequest()->getParam('ok', 0);
        $this->_view->assign('rid', $this->_cnf->common->roles->affiliate);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            $userData = $this->_model->getUserById($id);

            $errors = array();
            if (!$this->_valid->validateForm($form))
            {
                $errors = $this->_valid->getInput()->getMessages();
            }

            if ($userData["wmr"])
            {
                if (isset($form['wmr']))
                {
                    $errors["wmr"] = "Кошелек не может быть изменен";
                    $wmr = false;
                }
                $form["wmr"] = $userData["wmr"];
            }

            if (!empty($form["wmr"]))
            {
                if ((strlen($form["wmr"]) != 13) || (substr($form["wmr"], 0, 1) != "R") || !preg_match("/^\d+$/", substr($form["wmr"], 1)))
                {
                    $errors["wmr"] = "Неверно задан WMR";
                }
                else
                {
                    if ($wmid = $this->_getWmid($form["wmr"]))
                    {
                        $form["wmid"] = $this->_getWmid($form["wmr"]);
                    }
                    else
                    {
                        $errors["wmr"] = "Не существует такой WMR";
                    }
                }
            }

            if (!empty($errors))
            {
                echo json_encode(array('result' => 0, 'errors' => $errors));
                exit;
            }

            $this->_model->editUser($form, $id);
            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('user', $user);
        $html = $this->_view->fetch('UsersEditAffiliate.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * Добавление рекла
     */
    public function addmerchantAction()
    {
        $ok = $this->getRequest()->getParam('ok', 0);
        $idRecl = $this->getRequest()->getParam('idRecl', 0);
        $this->_view->assign('rid', $this->_cnf->common->roles->merchant);

        if (!$idRecl)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                echo json_encode(array('result' => 0, 'errors' => $this->_valid->getInput()->getMessages()));
                exit;
            }
            $form['role'] = $this->_cnf->common->roles->merchant;
            $form['idRecl'] =  $idRecl;
            $form['idUser'] =  $this->_model->addUser($form);

            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('idRecl', $idRecl);
        $html = $this->_view->fetch('UsersAddMerchant.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * Редактирование рекла
     */
    public function editmerchantAction()
    {
        $id = $this->getRequest()->getParam('id');
        $user = $this->getRequest()->getParam('user');
        $ok = $this->getRequest()->getParam('ok', 0);
        $this->_view->assign('rid', $this->_cnf->common->roles->merchant);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                echo json_encode(array('result' => 0, 'errors' => $this->_valid->getInput()->getMessages()));
                exit;
            }
            $this->_model->editUser($form, $id);
            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('user', $user);
        $html = $this->_view->fetch('UsersEditMerchant.tpl', array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }

    /**
     * json История логинов
     */
    public function loginhistoryAction()
    {
        $id = $this->getRequest()->getParam('id');

        if (!$id)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $pager = pagerPrepareDT($this->getRequest());
        $history = $this->_model->loginHistory($id, $pager);
        $data = pagerDataPrepareDT($history, $pager);

        echo json_encode(array(
            'result' => 1,
            'data' => $data));
        exit;
    }

    /**
     * получить json wmid по wmr
     */
    public function getwmidAction()
    {
        $wmr = $this->getRequest()->getParam('wmr');

        if (!$wmr)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $wmid = $this->_getWmid($wmr);

        echo json_encode(array(
            'result' => 1,
            'wmid' => $wmid));
        exit;
    }


    /**
     * json данные об аффиллиейте
     */
    public function affinfoAction()
    {
        $id = $this->getRequest()->getParam('id');

        if (!$id)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $user = $this->_model->getUserById($id);

        $this->_view->assign('user', $user);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


    /**
     * получить wmid по wmr
     */
    public function _getWmid($wmr)
    {
        if (!$wmr)
        {
            return 0;
        }

        $page = file_get_contents('https://passport.webmoney.ru/asp/CertView.asp?purse=' . $wmr);
        $page_exploded = explode('var wmid = \'', $page);
        if (isset($page_exploded[1]))
        {
            return $wmid = substr($page_exploded[1], 0, 12);
        }
        else
        {
            return 0;
        }
    }
}