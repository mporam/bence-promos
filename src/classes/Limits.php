<?php

namespace Bence;


class Limits
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getTiersForUser($uid) {
        $query = $this->db->prepare(
            "SELECT `tier`
            FROM `userTiers`
            WHERE uid = '$uid'"
        );

        $query->execute();
        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function getAvailableTiersForUser($accNo) {
        $userLimits = $this->getLimitsForUser($accNo);
        $userTotal = $this->getTotalForUser($accNo);
        $availableTiers = [];

        $availableTiers[1] = ($userTotal[0]['total'] >= $userLimits[0]['t1limit'] ? 1 : 0);
        $availableTiers[2] = ($userTotal[0]['total'] >= $userLimits[0]['t2limit'] ? 1 : 0);
        $availableTiers[3] = ($userTotal[0]['total'] >= $userLimits[0]['t3limit'] ? 1 : 0);
        $availableTiers[4] = ($userTotal[0]['total'] >= $userLimits[0]['t4limit'] ? 1 : 0);
        $availableTiers[5] = ($userTotal[0]['total'] >= $userLimits[0]['t5limit'] ? 1 : 0);

        return $availableTiers;
    }

    public function getLimitsForUser($accNo) {
        $query = $this->db->prepare(
            "SELECT
              t1limit,
              t2limit,
              t3limit,
              t4limit,
              t5limit
            FROM `limits2015`
            WHERE accNo = '$accNo'"
        );

        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    private function getTotalForUser($accNo) {
        $query = $this->db->prepare(
            "SELECT
              total 
            FROM `expenses2015`
            WHERE accNo = '$accNo';"
        );

        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }


}