<?php

class Recls_UsersController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('main_title', $this->_valid->getMessage('title'));
    }

    /**
     * отображения списка кампаний рекла
     */
    public function indexAction()
    {
        $cnf = Zend_Registry::get('cnf');
        $idRecl = $this->getRequest()->getParam('id', '');
        $users = $this->_model->getUsers($idRecl, $this->_cnf->common->roles->merchant);
                                            
        $this->_view->assign('idRecl', $idRecl);
        $this->_view->assign('users', $users);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idRecl' => $idRecl));
        exit;
    }

}