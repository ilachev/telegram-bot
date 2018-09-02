<?php


use Phinx\Seed\AbstractSeed;

class RedirectsSeeder extends AbstractSeed
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
                'redirect' => '88005353535',
                'user_id' => '1',
            ],
            [
                'redirect' => '380501234567',
                'user_id' => '1',
            ],
            [
                'redirect' => '380501234999',
                'user_id' => '3',
            ],
        ];

        $user = $this->table('redirects');
        $user->insert($data)->save();
    }
}
