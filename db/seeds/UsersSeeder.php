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
                'name' => 'imp_f',
                'phone' => '',
                'uid' => '505904694',
            ],
            [
                'name' => 'UT7AT',
                'phone' => '',
                'uid' => '252911662',
            ],
            [
                'name' => 'spr09',
                'phone' => '',
                'uid' => '276998190',
            ]
        ];

        $user = $this->table('users');
        $user->insert($data)->save();
    }
}
