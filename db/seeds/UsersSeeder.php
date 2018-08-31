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
                'username' => 'imp_f',
                'phone' => '79995153244',
                'full_name' => 'Карачев Илья',
                'chat_id' => '505904694',
            ],
            [
                'username' => 'UT7AT',
                'phone' => '',
                'full_name' => '',
                'chat_id' => '252911662',
            ],
            [
                'username' => 'spr09',
                'phone' => '',
                'full_name' => '',
                'chat_id' => '276998190',
            ]
        ];

        $user = $this->table('users');
        $user->insert($data)->save();
    }
}
