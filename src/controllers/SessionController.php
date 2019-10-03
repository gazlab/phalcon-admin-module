<?php

namespace Gazlab\Admin\Controllers;

use Gazlab\Admin\Models\User;

class SessionController extends ControllerBase
{
    public $menu = [
        'session'
    ];

    public function signInAction()
    {
        if ($this->request->isPost()) {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            if (isset($this->config->ldap->status) && $this->config->ldap->status === true) {
                $this->ldapCheck($username, $password);
            }

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


        view: $this->tag->prependTitle('Sign In');
        $this->view->pick($this->config->application->viewsDir . 'session/signIn');
    }

    public function signOutAction()
    {
        $this->view->disable();

        $this->session->destroy();

        return $this->response->redirect();
    }

    private function ldapCheck($username, $password)
    {
        if ($this->ldap->authenticate($username, $password)) {
            $data = $this->ldap->search()->where('uid', '=', $username)->get();
            if (count($data) > 0) {
                $user = User::findFirstByUsername($data[0]['uid']);
                if ($user) {
                    $user->password = $this->security->hash($password);
                } else {
                    $user = new User();
                    $user->username = $username;
                    $user->password = $this->security->hash($password);
                    $user->name = $data[0]['sn'];
                    if (isset($data[0]['mail'])) {
                        $user->email = $data[0]['mail'];
                    }
                    $user->profile_id = $this->config->ldap->defaultProfileId;
                    $user->options = json_encode($data[0]);

                    $avatarRelativePath = 'files/GaUsers/';
                    $avatarDir = BASE_PATH . '/public/' . $avatarRelativePath;
                    if (!is_dir($avatarDir)) {
                        mkdir($avatarDir);
                    }
                    $filename = $user->username . '.jpg';
                    $avatarPath = $avatarDir . $filename;
                    file_put_contents($avatarPath, file_get_contents('http://pwb-esshr.aon.telkom.co.id/index.php?r=pwbPhoto/profilePhoto&nik=' . $user->username));
                    $user->avatar = $avatarRelativePath . $filename;
                }
                $user->save();
            }
        }
    }
}
