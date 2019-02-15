<?php

namespace Bence;

class Stat
{
    private $db;
    private $totalUsers;

    public function __construct($db, $total)
    {
        $this->db = $db;
        $this->totalUsers = $total;
    }

    public function getPerformanceStatCircle($id, $tier) {

        $values = $this->calculateStatValues($tier);

        $stat = "<div id=\"$id\" class=\"stat\"></div>";

        $stat .= $this->getStatInfo($values['userCount'], $tier);

        $stat .= "<script>";
        $stat .= "$(\"#$id\").circliful({
                animationStep: 5,
                foregroundBorderWidth: 10,
                backgroundBorderWidth: 10,
                percent: " . $values['percent'] . ",
                foregroundColor: '#9A1A31'
            });";
        $stat .= "</script>";

        return $stat;
    }

    public function getUserStatCircle($id, $accNo, $tier) {

        $query = $this->db->prepare("SELECT `Total` FROM expenses2015 WHERE accNo = '$accNo';");
        $query->execute();
        $total = $query->fetch(\PDO::FETCH_ASSOC);

        $query = $this->db->prepare("SELECT t" . $tier . "limit AS 'limit' FROM limits2015 WHERE accNo = '$accNo';");
        $query->execute();
        $limit = $query->fetch(\PDO::FETCH_ASSOC);

        $values['percent'] = round(($total['Total'] / $limit['limit'])*100);

        $remaining = number_format($limit['limit']-$total['Total'],2,".",",");

        if ($total['Total'] > $limit['limit']) {
            $values['percent'] = 100;
        }

        $stat = "<div id=\"$id\" class=\"stat\"></div>";

        $tierName = \Bence\Promotions::getTierName($tier);

        if ($total['Total'] > $limit['limit']) {
            $stat .= "<div>You have unlocked " . $tierName . " rewards.</div>";
        } else {
            $stat .= "<div>You are <b>" . $values['percent'] . "%</b> of the way to unlocking " . $tierName . " rewards.
        Spend &pound;" . $remaining . " more to unlock them.</div>";
        }



        $stat .= "<script>";
        $stat .= "$(\"#$id\").circliful({
                animationStep: 5,
                foregroundBorderWidth: 10,
                backgroundBorderWidth: 10,
                percent: " . $values['percent'] . ",
                foregroundColor: '#9A1A31'
            });";
        $stat .= "</script>";

        return $stat;
    }

    private function getStatInfo($count, $tier) {
        return "<div><b>$count</b> customers have completed tier $tier out of
            <b>$this->totalUsers</b> customers<br><br><a href=\"/account/reachedTier/" . $tier . "\">View Users &gt;</a></div>";
    }

    private function calculateStatValues($tier) {
        $values = $this->getStatValueForTier($tier);
        $values['percent'] = ($values['userCount'] / $this->totalUsers)*100;
        return $values;
    }

    private function getStatValueForTier($tier) {
        $query = $this->db->prepare("
            SELECT count(users.id) AS 'userCount'
             FROM `users`
             LEFT JOIN `expenses2015` ON users.accNo = expenses2015.accNo
             LEFT JOIN `limits2015` ON users.accNo = limits2015.accNo
                WHERE `expenses2015`.`total` > `limits2015`.`t" . $tier . "limit`
				AND `limits2015`.`t" . $tier . "limit` > 0;
        ");

        $query->execute();
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

}