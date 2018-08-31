<?php


use Phinx\Seed\AbstractSeed;

class ExtensionsSeeder extends AbstractSeed
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
                'user_id' => '1',
                'extension' => '100',
            ],
            [
                'user_id' => '2',
                'extension' => '102',
            ],
            [
                'user_id' => '3',
                'extension' => '123',
            ],
        ];

        $user = $this->table('extensions');
        $user->insert($data)->save();
    }
}
