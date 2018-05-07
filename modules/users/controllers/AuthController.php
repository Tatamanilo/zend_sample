<?php

class Users_AuthController extends MyController
{
    function init()
    {
        parent::init();
    }

    public function authenticateAction()
    {
        $ok = $this->getRequest()->getParam('ok_auth');

        if (!isAuthed('back'))
        {
            if ($ok == 'ok')
            {
                $form = $this->getRequest()->getParam('form');
                $userType = $this->getRequest()->getParam('user_type', 'user');
                $this->_view->assign($userType . 'Form', $form);

                if (!$this->_valid->validateForm($form))
                {
                    $this->_view->assign('auth_errors', $this->_valid->getInput()->getMessages());
                    $this->_view->addView('AuthForm.tpl', $this->_mca);
                    return;
                }

                if (authenticate($form['login'], $form['pass']) === true)
                {
                    list($empty, $module, $controller, $action) = explode('/', $this->_cnf->common->defBpPage);
                    $this->forward($action, $controller, $module, true);
                }

                $this->_view->assign('auth_errors', $this->_valid->getMessage('auth_error'));
                $this->_view->addView('AuthForm.tpl', $this->_mca);
            }
            else
            {
                $this->_view->addView('AuthForm.tpl', $this->_mca);
            }
        }
        else
        {
            list($empty, $module, $controller, $action) = explode('/', $this->_cnf->common->defBpPage);
            $this->forward($action, $controller, $module, true);
        }
    }

    public function exitAction()
    {
        $namespace = new Zend_Session_Namespace('Zend_Auth');
        $namespace->__unset('front');

        $namespaceForward = new Zend_Session_Namespace('Zend_Forward');
        if (isset($namespaceForward->forwardData))
        {
            unset($namespaceForward->forwardData);
        }

        list(, $module, $controller, $action) = explode('/', $this->_cnf->common->defPage);

        $this->forward($action, $controller, $module, true);
        exit;
    }

    public function forgetpasswordAction()
    {
        $ok = $this->getRequest()->getParam('send');

        if ($ok == 'ok')
        {
            $form = $this->getRequest()->getParam('form');
            if (!$this->_valid->validateForm($form))
            {
                $this->_view->assign('errors', $this->_valid->getInput()->getMessages());
                $this->_view->assign('form', $form);
                $this->_view->addView('ForgetPassword.tpl', $this->_mca);
                return;
            }

            if ($this->_model->checkLogin($form['email']))
            {
                $newPassword = mkPass();
                $this->_model->setNewPassword($newPassword, $form['email']);

                $user = $this->_model->getUserByLogin($form['email']);
                $messageBody = $this->_view->fetch('ForgetEmail.tpl',
                                                array('user' => $user, 'password' => $newPassword),
                                                $this->_mca);

                $to = $form['email'];
//                $from = getSettingByName('noReplyEmail');
//                $SMTP = getSettingByName('smtpName');

                $mail = new Mailer();
                $mail->addMailData(
                    $this->_valid->getMessage('forgetMailSubject'),
                    $messageBody,
                    $to
//                    $from
                    );
                $mail->sendMessages();

                $this->_view->assign('send', 1);
                $this->_view->addView('ForgetPassword.tpl', $this->_mca);

                return;
            }
            else
            {
                $this->_view->assign('errors', $this->_valid->getMessage('wrongEmail'));
                $this->_view->assign('form', $form);
                $this->_view->addView('ForgetPassword.tpl', $this->_mca);
                return;
            }
        }
        else
        {
            $this->_view->addView('ForgetPassword.tpl', $this->_mca);
        }
    }

}