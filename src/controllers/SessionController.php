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

    public function signOutAction()
    {
        $this->view->disable();

        $this->session->destroy();

        return $this->response->redirect();
    }

    public function profileAction()
    {
        if ($this->request->isPost()) {
            $this->userSession->username = $this->request->getPost('username');
            if (!$this->userSession->save()) {
                foreach ($this->userSession->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flashSession->success('Data has been save');
                return $this->response->redirect(join('/', [$this->router->getControllerName(), $this->router->getActionName()]));
            }
        }

        $this->view->pick($this->config->application->viewsDir . 'session/profile');
    }
}
