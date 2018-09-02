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
                'chat_id' => '505904694',
                'user_id' => '1',
            ],
            [
                'chat_id' => '2',
                'user_id' => '2',
            ],
            [
                'chat_id' => '3',
                'user_id' => '3',
            ],
        ];

        $user = $this->table('chats');
        $user->insert($data)->save();
    }
}
