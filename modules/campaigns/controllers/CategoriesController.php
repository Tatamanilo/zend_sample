<?php

class Campaigns_CategoriesController extends MyController
{
    /** @var CategoriesModel $_model */
    protected $_model = null;

    /**
     * Add new record.
     */
    public function addAction()
    {
        $idParentCategory = $this->getRequest()->getParam('idParentCategory', 0) + 0;
        if(!$idParentCategory || !$this->_model->existsCategory($idParentCategory))
        {
            $idParentCategory = 1;
        }
        $isAjax = $this->getRequest()->getParam('isAjax', false);
        $ok = $this->getRequest()->getParam('ok', 0);
        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                echo json_encode(array('result' => 0, 'errors' => $this->_valid->getInput()->getMessages()));
                exit;
            }
            $this->_model->addCategory($form, $idParentCategory);
            echo json_encode(array(
                'idParentCategory' => $idParentCategory,
                'result' => 1
            ));
            exit;
        }
        $this->_view->assign('idParentCategory', $idParentCategory);
        if ($isAjax) {
            $html = $this->_view->fetch('CategoriesAdd.tpl', array(), $this->_mca);
            echo json_encode(array('result' => 1, 'html' => $html));
            exit;
        }

        $this->_view->addView($this->_defaultTpl, $this->_mca);
    }

    /**
     * Delete exists record.
     */
    public function deleteAction()
    {
        $idCategory = $this->getRequest()->getParam('idCategory', false);
        $aCategory = $this->_model->getCategory($idCategory);
        if (!empty($aCategory))
        {
            $this->_model->delete($idCategory, true);
            echo json_encode(array(
                'idParentCategory' => $aCategory['idParentCategory'],
                'result' => 1
            ));
            exit;
        }
        else
        {
            list(, $module, $controller, $action) = explode('/', $this->_cnf->common->defBpPage);
            $this->forward($action, $controller, $module, true);
        }
    }

    /**
     * Edit exists record.
     */
    public function editAction()
    {
        $idCategory = $this->getRequest()->getParam('idCategory', false);
        $isAjax = $this->getRequest()->getParam('isAjax', false);
        $aCategory = $this->_model->getCategory($idCategory);
        if (!empty($aCategory))
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
                $this->_model->editCategory($form, $idCategory);
                echo json_encode(array(
                    'idParentCategory' => $aCategory['idParentCategory'],
                    'result' => 1
                ));
                exit;
            }
            $this->_view->assign('idCategory', $idCategory);
            $this->_view->assign('categoryDescr', $aCategory['categoryDescr']);
            $this->_view->assign('categoryName', $aCategory['categoryName']);
            if ($isAjax) {
                $html = $this->_view->fetch('CategoriesEdit.tpl', array(), $this->_mca);
                echo json_encode(array('result' => 1, 'html' => $html));
                exit;
            }

            $this->_view->addView($this->_defaultTpl, $this->_mca);
        }
        else
        {
            list(, $module, $controller, $action) = explode('/', $this->_cnf->common->defBpPage);
            $this->forward($action, $controller, $module, true);
        }
    }

    /**
     * Index page for categories.
     */
    public function indexAction()
    {
        $this->_view->assign('main_title', $this->_valid->getMessage("title"));
        $this->_view->addView($this->_defaultTpl, $this->_mca);
    }

    /**
     * Return list of categories in JSON.
     */
    public function listAction()
    {
        $idParentCategory = $this->getRequest()->getParam('idParentCategory', NULL)+0;

        if(!$idParentCategory)
        {
            $idParentCategory = 1;
        }
        $data = $this->_model->getChildren($idParentCategory);
        $aResult = array();
        foreach($data as $a)
        {
            $aResult[] = array(
                'id' => $a['idCategory'],
                'text' => htmlspecialchars($a['categoryName']),
                'children' => $a['hasChild']
            );
        }
        header('Content-Type: application/json; charset=utf8');
        echo json_encode($aResult);
        exit;
    }

    /**
     * Move record.
     */
    public function moveAction()
    {
        $idCategory = (int) $this->getRequest()->getParam('idCategory', 0);
        $idParentCategory = (int) $this->getRequest()->getParam('idParentCategory', 0);
        if (!$idParentCategory)
        {
            $idParentCategory = 1;
        }
        // Add 1, because treejs counting position from zero
        $orderNum = (int) $this->getRequest()->getParam('orderNum', 0) + 1;
        $aCategory = $this->_model->getCategory($idCategory);
        $aCategoryParent = $this->_model->getCategory($idParentCategory);
        if (empty($aCategory) || empty($aCategoryParent))
        {
            list(, $module, $controller, $action) = explode('/', $this->_cnf->common->defBpPage);
            $this->forward($action, $controller, $module, true);
            return;
        }

        $this->_model->moveCategory($aCategory, $idParentCategory, $orderNum);

        echo json_encode(array('result' => 1));
        exit;
    }
}