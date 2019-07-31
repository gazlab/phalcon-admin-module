<?php

namespace Gazlab\Admin\Controllers;

class SessionController extends ControllerBase
{
    public function signInAction()
    {
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            if (isset($this->config->ldap->status) && $this->config->ldap->status === true) {
                $this->ldapCheck($username, $password);
            }

            $user = \Gazlab\Admin\Models\Users::findFirstByUsername($username);

            if (!$user) {
                $this->flash->warning("Username " . $username . " doesn't exist.");
                goto form;
            }

            if (!$this->security->checkHash($password, $user->password)) {
                $this->flash->error('Password is wrong');
                goto form;
            }

            $this->session->set('uId', $user->id);
            $redirect = $this->request->has('r') ? $this->request->get('r') : $this->router->getModuleName();
            return $this->response->redirect($redirect);
        }

        form: $this->tag->prependTitle('Sign In ');
        $this->view->pick($this->config->application->viewsDir . 'session/signIn');
    }

    public function signOutAction()
    {
        $this->session->destroy();
        $redirect = $this->request->has('r') ? $this->request->get('r') : $this->router->getModuleName();
        return $this->response->redirect($redirect);
    }

    private function ldapCheck($username, $password)
    {
        if ($this->ldap->authenticate($username, $password)) {
            $data = $this->ldap->search()->where('uid', '=', $username)->get();
            if (count($data) > 0) {
                $user = \Gazlab\Admin\Models\Users::findFirstByUsername($data[0]['uid']);
                if ($user) {
                    $user->password = $this->security->hash($password);
                } else {
                    $user = new \Gazlab\Admin\Models\Users();
                    $user->username = $username;
                    $user->password = $this->security->hash($password);
                    $user->name = $data[0]['cn'];
                    if (isset($data[0]['mail'])) {
                        $user->email = $data[0]['mail'];
                    }
                    $user->profile_id = $this->config->ldap->defaultProfileId;
                    $user->options = json_encode($data[0]);
                }
                $user->save();
            }
        }
    }
}
