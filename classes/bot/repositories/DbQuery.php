<?php

namespace pcs\bot\repositories;

use PDO;

class DbQuery
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getNumbers()
    {
        return $this->db->query('SELECT subs.number, users.name,numbers.tg_number FROM subs '
            . 'JOIN users ON users.uid = subs.uid '
            . 'JOIN numbers ON subs.number=numbers.internal')->fetchAll(2);
    }

    public function getAllNumbers()
    {
        return $this->db->query('SELECT * FROM numbers')->fetchAll(2);
    }
}