<?php
namespace Gazlab\Admin\Controllers;

class SessionController extends ControllerBase
{
    public function signInAction()
    {
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $user = \Gazlab\Admin\Models\Users::findFirstByUsername($username);

            if (!$user) {
                $this->flash->warning("Username " . $username . " doesn't exist.");
                goto form;
            }

            if (!$this->security->checkHash($this->request->getPost('password'), $user->password)) {
                $this->flash->error('Password is wrong');
                goto form;
            }

            $this->session->set('uId', $user->id);
            return $this->response->redirect($this->router->getModuleName());
        }

        form:
        $this->tag->prependTitle('Sign In ');
        $this->view->pick($this->config->application->viewsDir . 'session/signIn');
    }

    public function signOutAction()
    {
        $this->session->destroy();
        return $this->response->redirect($this->router->getModuleName());
    }
}
