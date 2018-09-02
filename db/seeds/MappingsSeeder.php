<?php


use Phinx\Seed\AbstractSeed;

class MappingsSeeder extends AbstractSeed
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
                'country' => 'Украина',
                'mapping' => '380*********',
            ],
            [
                'country' => 'Россия',
                'mapping' => '79*********',
            ],
        ];

        $user = $this->table('mappings');
        $user->insert($data)->save();
    }
}
