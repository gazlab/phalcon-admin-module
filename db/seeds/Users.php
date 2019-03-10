<?php

use Phinx\Seed\AbstractSeed;

class Users extends AbstractSeed
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
        $security = new \Phalcon\Security();
        $data = [
            [
                'profile_id' => 1,
                'username' => 'admin',
                'password' => $security->hash('admin'),
            ],
        ];

        $table = $this->table('users');
        $table->insert($data)
            ->save();
    }

    public function getDependencies()
    {
        return [
            'Profiles',
        ];
    }
}
