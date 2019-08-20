<?php


use Phinx\Seed\AbstractSeed;
use Phalcon\Security;

class Users extends AbstractSeed
{
    public function getDependencies()
    {
        return [
            'Profiles'
        ];
    }

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
        $security = new Security();
        $data = [
            [
                'profile_id' => 1,
                'username'    => 'admin',
                'password'  => $security->hash('admin')
            ]
        ];

        $table = $this->table('users');
        $table->insert($data)
            ->save();
    }
}
