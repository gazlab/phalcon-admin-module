<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\User;

class SessionController extends ControllerBase
{
    public function signInAction()
    {
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $user = User::findFirstByUsername($this->request->getPost('username'));
                if (!$user) {
                    $this->flash->warning("Username doesn't exist");
                    goto form;
                }

                if (!$this->security->checkHash($this->request->getPost('password'), $user->password)) {
                    $this->flash->error("Password is wrong");
                    goto form;
                }

                if ($user->status_active == 0) {
                    $this->flash->warning("Account is not active");
                    goto form;
                }

                $this->session->set('uId', $user->id);
                return $this->response->redirect();
            }
        }

        form:;
    }

    public function signOutAction()
    {
        $this->session->destroy();

        return $this->response->redirect();
    }
}
