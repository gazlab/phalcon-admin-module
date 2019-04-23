<?php
namespace Gazlab\Admin\Controllers;

class UsersController extends ResourceController
{
    public function getResourceName()
    {
        return 'Users';
    }

    public function queryGetAll()
    {
        $criteria = [];

        if ($this->userSession->id != 1) {
            $criteria = [
                "created_by = ?0",
                'bind' => [
                    $this->userSession->id,
                ],
            ];
        }

        return \Users::find($criteria);
    }

    public function queryGetOne()
    {
        return \Users::findFirst($this->dispatcher->getParams()[0]);
    }

    public function table()
    {
        $this->column(['id']);
        $this->column(['username']);
        $this->column(['name']);

        $this->actions();
    }

    public function form()
    {
        // if ($this->isUpdateAction()) {
        // $profiles = [];
        // $profilesToUsers = \ProfilesToUsers::find([
        //     "user_id = ?0",
        //     'bind' => [
        //         $this->tag->getValue('id'),
        //     ],
        // ]);
        // foreach ($profilesToUsers as $profileToUser) {
        //     array_push($profiles, $profileToUser->profile_id);
        // }
        if ($this->isUpdateAction()) {
            $user = $this->queryGetOne();
            $this->tag->setDefault('profiles_to_user', $user->profilesToUsers[0]->profile_id);
        }
        $this->select(['profiles_to_user', \Profiles::find(["active = 'Y'"]), 'using' => ['id', 'name'], 'label' => 'Role Profile']);
        // }

        $this->textField(['name']);
        $this->textField(['username']);

        if ($this->isUpdateAction()) {
            $this->passwordField(['reset_password']);
        } else {
            $this->passwordField(['password']);
        }

        // $this->fileField(['avatar', 'label' => 'Foto']);
        // $this->textField(['birthday', 'class' => 'date-picker']);
        $this->selectStatic(['active', ['Y' => 'Active', 'N' => 'Not Active'], 'label' => 'Status Active']);
    }

    public function deleteAction()
    {
        $user = $this->queryGetOne();

        $this->db->begin();
        foreach ($user->getProfilesToUsers() as $profile) {
            if (!$profile->delete()) {
                foreach ($profile->getMessages() as $error) {
                    $this->flash->error($error);
                }
                $this->db->rollback;
                return;
            }
        }
        if (!$user->delete()) {
            foreach ($user->getMessages() as $error) {
                $this->flash->error($error);
            }
            $this->db->rollback;
            return;
        }
        $this->db->commit();

        $this->flashSession->warning('Data has been deleted.');

        return $this->response->redirect($this->router->getModuleName() . '/' . $this->router->getControllerName());
    }

    public function params()
    {
        $params = [];

        $profileId = $this->request->getPost('profiles_to_user');
        if ($this->isCreateAction()) {
            $profilesToUsers = new \ProfilesToUsers();
            $profilesToUsers->profile_id = $profileId;
            $params['profilesToUsers'] = $profilesToUsers;
        }
        if ($this->isUpdateAction()) {
            $profilesToUsers = \ProfilesToUsers::findFirstByUserId($this->dispatcher->getParams()[0]);
            $profilesToUsers->profile_id = $profileId;
            $params['profilesToUsers'] = $profilesToUsers;
        }

        if (!empty($this->request->getPost('password'))) {
            $params['password'] = $this->security->hash($this->request->getPost('password'));
        }

        if ($this->request->hasPost('reset_password')) {
            $params['password'] = $this->security->hash($this->request->getPost('reset_password'));
        }

        return array_replace($this->request->getPost(), $params);
    }
}
