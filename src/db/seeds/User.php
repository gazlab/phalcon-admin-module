<?php

use Phalcon\Security;
use Phinx\Seed\AbstractSeed;

class User extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $security = new Security;

        $data = [
            [
                'profile_id' => 1,
                'username'    => 'admin',
                'password'    => $security->hash('admin')
            ]
        ];

        $table = $this->table('user');
        $table->insert($data)
            ->save();
    }

    public function getDependencies()
    {
        return [
            'Profile'
        ];
    }

}
