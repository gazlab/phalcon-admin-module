<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\User;

class SessionController extends ControllerBase
{
    public function signInAction()
    {
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $user = User::findFirstByUsername($username);
            if (!$user) {
                $this->flash->warning('Account "' . $username . '" doesn\'t exist on system');
                goto view;
            }

            if ($user->blocked == 1) {
                $this->flash->warning('Account is blocked, please call administrator');
                goto view;
            }

            if (!$this->security->checkHash($password, $user->password)) {
                $this->flash->error('Wrong password');
                goto view;
            }

            $this->session->set('uId', $user->id);

            return $this->response->redirect();
        }

        view: $this->view->pick($this->config->application->viewsDir . 'session/signIn');
    }
}
