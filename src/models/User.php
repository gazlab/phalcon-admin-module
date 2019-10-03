<?php

namespace Gazlab\Admin\Models;

class User extends ModelBase
{
    public function initialize()
    {
        $this->setSource('ga_users');

        $this->belongsTo('profile_id', 'Gazlab\Admin\Models\Profile', 'id', ['alias' => 'Profile']);

        parent::initialize();
    }

    public function afterFetch()
    {
        if (is_null($this->avatar)) {
            $gravatar = $this->getDI()->get('gravatar');
            $this->avatar = $gravatar->getAvatar($this->username);
        }
    }
}
