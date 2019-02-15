<?php

namespace Bence;


class Expenses
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getExpensesByAccNo($accNo) {
        $query = $this->db->prepare("
            SELECT * FROM `expenses2015` WHERE `accNo` = '$accNo';
        ");

        $query->execute();
        return $this->removeAccNo($query->fetch(\PDO::FETCH_ASSOC));
    }

    private function removeAccNo($expenses) {
        unset($expenses['accNo']);
        return $expenses;
    }

}