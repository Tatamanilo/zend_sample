<?php

class Campaigns_CampaignsController extends MyController
{
    function init()
    {
        parent::init();
        $this->_view->assign('main_title', $this->_valid->getMessage('title'));
    }

    /**
     * отображения списка офферов
     */
    public function indexAction()
    {
        $cnf = Zend_Registry::get('cnf');

        $this->_view->assign('offersUrl', $cnf->url->offers);
        $this->_view->assign('targets', $this->_model->getTargets());
        $this->_view->assign('countries', $this->_model->getCountries());
        $this->_view->addView($this->_defaultTpl, $this->_mca);
    }

    /**
     * Возвращает json список офферов
     */
    public function campaignsAction()
    {
        $pager = pagerPrepareDT($this->getRequest());
        $form = $this->getRequest()->getParam('form', 0);
        /*
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
        } */


        $users = $this->_model->getCampaigns($pager, $form);
        $data = pagerDataPrepareDT($users, $pager);

        echo json_encode(array(
            'result' => 1,
            'data' => $data));
        exit;
    }

    /**
     * детальная информация об оффере, вкладки прокладок, лендингов, комиссий
     */
    public function expandAction()
    {
        $idCampaign = $this->getRequest()->getParam('id', '');

        $this->_view->assign('idCampaign', $idCampaign);
        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html, 'idCampaign' => $idCampaign));
        exit;
    }

    /**
     * Отображение и обработка формы добавления оффера
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

            if (isset($form['sources']))
            {
                $form['sources'] = array_sum($form['sources']);
            }
            else
            {
                $errors["sources"] = 'Необходимо выбрать источники трафика';
            }

            /*
            if (isset($form['idRecls']))
            {
                $idRecls = explode(";", $form['idRecls']);
                unset($form['idRecls']);
            }
            */

            if (!empty($errors))
            {
                echo json_encode(array('result' => 0, 'errors' => $errors));
                exit;
            }

            $idCampaign = $this->_model->addCampaign($form);

            // create default general commission setion
            Zend_Loader::loadClass("CommissionsModel", $this->_cnf->path->modulesFront . "campaigns" . $this->_cnf->path->models);
            $_commissionsModel = new CommissionsModel;
            $commissions = $_commissionsModel->addCommissionSection($idCampaign);

            //$this->_model->addCampaignRecls($idCampaign, $idRecls);
            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('sources', $this->_model->getSources());

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


    /**
     * Отображение и обработка формы редактирования оффера
     */
    public function editAction()
    {
        $cnf = Zend_Registry::get('cnf');

        $ok = $this->getRequest()->getParam('ok', 0);
        $idCampaign = $this->getRequest()->getParam('id', 0);

        if (!$idCampaign)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }

        $campaign = $this->_model->getCampaign($idCampaign);

        if ($ok)
        {
            $form = $this->getRequest()->getParam('form');

            $errors = array();
            if (!$this->_valid->validateForm($form))
            {
                $errors = $this->_valid->getInput()->getMessages();
            }

            if (isset($form['sources']))
            {
                $form['sources'] = array_sum($form['sources']);
            }
            else
            {
                $errors["sources"] = 'Необходимо выбрать источники трафика';
            }

            if (!empty($errors))
            {
                echo json_encode(array('result' => 0, 'errors' => $errors));
                exit;
            }

            // delete previous logo if new one is uploaded
            if (!empty($form["logo"]))
            {
                @unlink($cnf->path->offers.$campaign["logo"]);
            }
            else
            {
                unset($form["logo"]);
            }
            // delete previous promoMaterials if new one is uploaded
            if (!empty($form["promoMaterials"]))
            {
                @unlink($cnf->path->offers.$campaign["promoMaterials"]);
            }
            else
            {
                unset($form["promoMaterials"]);
            }

            $this->_model->editCampaign($idCampaign, $form);


            echo json_encode(array('result' => 1));
            exit;
        }
        $this->_view->assign('sources', $this->_model->getSources());
        $this->_view->assign('campaign', $campaign);
        $this->_view->assign('idCampaign', $idCampaign);
        $this->_view->assign('offersUrl', $cnf->url->offers);

        $html = $this->_view->fetch($this->_defaultTpl, array(), $this->_mca);
        echo json_encode(array('result' => 1, 'html' => $html));
        exit;
    }


    /**
     * жсон список офферов для автодополнения
     */
    public function campaignslistAction()
    {
        $term = $this->getRequest()->getParam('term', '');
        $idRecl = $this->getRequest()->getParam('idRecl', false);

        $list = $this->_model->getCampaignsList($term, $idRecl);
        echo json_encode($list);
        exit;
    }

    /**
     * жсон список стран для автодополнения
     */
    public function countrieslistAction()
    {
        $term = urldecode($this->getRequest()->getParam('term', ''));

        $list = $this->_model->getCountriesList($term);
        echo json_encode($list);
        exit;
    }

    /**
     * загрузка логотипа
     */
    public function logouploadAction()
    {
        $cnf = Zend_Registry::get('cnf');

        $uploader = Uploader::getInstance();
        $uploader->setFilesSize(1048576*8);//8Mb
        $uploader->setExtensions('jpg,gif,png,zip');
        $uploader->setFileNames(array(
            'logoFile' => $cnf->path->offers . uuid()
        ));
        $uploader->setIgnoreEmpty(array('logoFile'));
        if ($uploader->upload())
        {
            //Лого оффера
            if (strlen($uploader->getFileName('logoFile', false)) > 0)
            {
                $file = $uploader->getFileName('logoFile');
                $fileName = $uploader->getFileName('logoFile', false);

                $graph = Graphic::getInstance();
                $graph->createPreview($file, $file, '150x150');
            }
        }
        $data = array();
        $data["files"][] = array(
            "url" => $cnf->url->offers.$fileName,
            "thumbnail_url" => $cnf->url->offers.$fileName,
            "name" => $fileName,
            "type" => "image/jpeg",
            "size" => 46353,
            "delete_url" => "http://url.to/delete /file/",
            "delete_type" => "DELETE"
        );
        echo json_encode($data);
        exit;
    }

    /**
     * загрузка промо материалов
     */
    public function promouploadAction()
    {
        $cnf = Zend_Registry::get('cnf');

        $uploader = Uploader::getInstance();
        $uploader->setFilesSize(1048576*8);//8Mb
        //$uploader->setExtensions('jpg,gif,png,zip');
        $uploader->setFileNames(array(
            'promoMaterialsFile' => $cnf->path->offersPromo . uuid()
        ));
        $uploader->setIgnoreEmpty(array('promoMaterialsFile'));
        if ($uploader->upload())
        {
            //Промо оффера
            if (strlen($uploader->getFileName('promoMaterialsFile', false)) > 0)
            {
                $file = $uploader->getFileName('promoMaterialsFile');
                $fileName = $uploader->getFileName('promoMaterialsFile', false);
            }
        }
        $data = array();
        $data["files"][] = array(
            "url" => $cnf->url->offersPromo.$fileName,
            "name" => $fileName,
            "size" => 46353,
            "delete_url" => "http://url.to/delete /file/",
            "delete_type" => "DELETE"
        );
        echo json_encode($data);
        exit;
    }


    /**
     * Операция изменения статуса оффера
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
     * Операция изменения типа оффера
     */
    public function changetypeAction()
    {
        $id = $this->getRequest()->getParam('id', 0);
        $changeTo = $this->getRequest()->getParam('change_to', 0);

        if (!$id || !$changeTo)
        {
            echo json_encode(array('result' => 0, 'errors' => "Пустые данные"));
            exit;
        }
        if ($this->_model->changeType($id, $changeTo))
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
     * Возвращает json список кампаний для автодополнения
     */
    public function campaignssearchlistAction()
    {
        $term = urldecode($this->getRequest()->getParam('term', ''));

        $list = $this->_model->getCampaignsList($idRole, $term);
        echo json_encode($list);
        exit;
    }
}