<?php
namespace Gazlab\Admin\Controllers;

class UsersController extends ResourceController
{
    public $menu = [
        'users',
        'icon' => 'fa fa-user',
        'name' => 'Users',
        'type' => 'hide',
    ];

    public function profileAction()
    {
        if ($this->request->isPost()) {
            $oldPassword = $this->request->getPost('old_password');
            $newPassword = $this->request->getPost('new_password');
            if (!empty($oldPassword) && !empty($newPassword)) {
                if (!$this->security->checkHash($oldPassword, $this->userSession->password)) {
                    $this->flash->error('Old Password is wrong.');
                    goto form;
                }

                $confirmPassword = $this->request->getPost('confirm_password');
                if ($newPassword !== $confirmPassword) {
                    $this->flash->error('New and Confirmation Password doesn\'t match.');
                    goto form;
                }

                $this->userSession->password = $this->security->hash($confirmPassword);
            }
            $this->userSession->username = $this->request->getPost('username');

            if (!$this->userSession->save()) {
                foreach ($this->userSession->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }

            $this->flash->success('Data has been updated');
        }

        form:
        $this->view->pick($this->config->application->viewsDir . '/session/userProfile');
    }
}
