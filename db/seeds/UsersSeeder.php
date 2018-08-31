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
                'phone' => '',
                'chat_id' => '505904694',
            ],
            [
                'username' => 'UT7AT',
                'phone' => '',
                'chat_id' => '252911662',
            ],
            [
                'username' => 'spr09',
                'phone' => '',
                'chat_id' => '276998190',
            ]
        ];

        $user = $this->table('users');
        $user->insert($data)->save();
    }
}
