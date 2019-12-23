<?php

namespace Gazlab\Admin\Controllers;

class UsersController extends ControllerBase
{
    public function profileAction()
    {
        if ($this->request->isPost()) {
            if ($this->security->checkToken()) {
                $user = $this->userSession;
                if ($this->request->hasPost('username')) {
                    $user->username = $this->request->getPost('username');
                    if (!$user->save()) {
                        foreach ($user->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    } else {
                        $this->flashSession->success('Data has been saved.');
                        return $this->response->redirect('/session/profile');
                    }
                }

                if ($this->request->hasPost('old_password') or $this->request->hasPost('new_password') or $this->request->hasPost('confirm_password')) {
                    if (!$this->security->checkHash($this->request->getPost('old_password'), $user->password)) {
                        $this->flash->error("Old Password is wrong");
                        goto form;
                    }

                    if ($this->request->getPost('new_password') !== $this->request->getPost('confirm_password')) {
                        $this->flash->error("New & Confirmation Password not match");
                        goto form;
                    }

                    $user->password = $this->security->hash($this->request->getPost('new_password'));
                    if (!$user->save()) {
                        foreach ($user->getMessages() as $message) {
                            $this->flash->error($message);
                        }
                    } else {
                        $this->flashSession->success('Password has been changed.');
                        return $this->response->redirect('/session/profile#change_password');
                    }
                }
            }
        }

        form:;
    }
}
