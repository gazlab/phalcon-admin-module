<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\User;

class UsersController extends ResourceController
{
    public $menu = [
        'users',
        'name' => 'Users',
        'icon' => 'fa fa-user'
    ];

    public function queryGetAll()
    {
        return $this->modelsManager->createBuilder()
            ->from('Gazlab\Admin\Models\User');
    }

    public function table()
    {
        $this->column(['avatar', 'header' => '', 'dataTable' => ['searchable' => false, 'orderable' => false, 'render' => $this->view->getPartial($this->config->application->viewsDir . 'users/_avatar.js')]]);
        $this->column(['username']);
        $this->column(['blocked', 'header' => 'Active', 'dataTable' => ['searchable' => false, 'orderable' => false, 'render' => 'return data == 0 ? "<i class=\"fa fa-check-circle text-success\" title=\"Active\"></i>" : "<i class=\"fa fa-close text-danger\"></i>"']]);
        
        $this->actions();
    }

    public function profileAction()
    {
        if ($this->request->isPost()) {
            $this->userSession->username = $this->request->getPost('username');
            if ($this->request->hasPost('new_password') && !empty($this->request->getPost('new_password'))) {
                if (!$this->security->checkHash($this->request->getPost('old_password'), $this->userSession->password)) {
                    $this->flash->error('Wrong old password.');
                    goto view;
                }

                if ($this->request->getPost('new_password') !== $this->request->getPost('confirm_password')) {
                    $this->flash->error('Wrong comfirm password.');
                    goto view;
                }

                $this->userSession->password = $this->security->hash($this->request->getPost('new_password'));
            }

            if (!$this->userSession->save()) {
                foreach ($this->userSession->getMessages() as $message) {
                    $this->flash->error($message);
                }
            } else {
                $this->flashSession->success('Data has been save');
                return $this->response->redirect(join('/', [$this->router->getControllerName(), $this->router->getActionName()]));
            }
        }

        view: $this->view->pick($this->config->application->viewsDir . 'session/profile');
    }

    public function profileHistoryAction()
    {
        $this->dispatcher->forward([
            'controller' => 'log-activities',
            'action' => 'index',
            'params' => ['ga_users', $this->userSession->id]
        ]);
    }
}
