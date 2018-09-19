<?php


use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
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
                'extension' => '100',
                'phone' => '79995153244',
                'full_name' => 'Карачев Илья',
            ],
            [
                'extension' => '200',
                'phone' => '',
                'full_name' => '',
            ],
            [
                'extension' => '096',
                'phone' => '',
                'full_name' => '',
            ]
        ];

        $user = $this->table('users');
        $user->insert($data)->save();
    }
}
