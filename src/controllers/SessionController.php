<?php

namespace Gazlab\Admin\Controllers;

class SessionController extends ControllerBase
{
    public function signInAction()
    {
        if ($this->request->isPost()){
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
        }
    }
}
