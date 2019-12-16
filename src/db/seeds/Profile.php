<?php


use Phinx\Seed\AbstractSeed;

class Profile extends AbstractSeed
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
        $data = [
            [
                'name'    => 'Administrators'
            ]
        ];

        $table = $this->table('profile');
        $table->insert($data)
            ->save();
    }
}
