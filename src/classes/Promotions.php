<?php

namespace Bence;


class Promotions
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getPromotions($limit = false) {
        $query = $this->db->prepare("SELECT * FROM `promotions2015`");

        if ($limit && is_int($limit)) {
            $query = $this->db->prepare("SELECT * FROM `promotions2015` ORDER BY RAND() LIMIT $limit");
        }

        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getHomepagePromotions() {
        $query = $this->db->prepare("SELECT * FROM `promotions2015` WHERE `tier` = 2 ORDER BY RAND() LIMIT 4;");
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPromotionsForUser($uid) {
        $query = $this->db->prepare("
            SELECT `tier` FROM `userTiers`
                WHERE `userTiers`.`uid` = $uid;
        ");
        $query->execute();
        $tiers = $query->fetchAll(\PDO::FETCH_ASSOC);

        $promos = [];

        foreach($tiers as $tier) {
            $promos[$this->getTierName($tier["tier"])] = $this->getPromotionsByTier($tier["tier"]);
        }
        return $promos;
    }

    static public function getTierName($tier) {
        $tiers = [
            "1" => "The Cherokee",
            "2" => "The Cheyenne",
            "3" => "The Chinook"
        ];

        return $tiers[$tier];
    }

    public function getPromotionsByTier($tier) {
        $query = $this->db->prepare("
            SELECT * FROM `promotions2015`
                WHERE `tier` = $tier;
        ");

        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getPromotionById($pid) {
        $query = $this->db->prepare("
            SELECT * FROM `promotions2015`
                WHERE `id` = $pid;
        ");

        $query->execute();
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function setPromotionForUser($promo, $uid) {
        $query = $this->db->prepare("UPDATE users SET `promo`='$promo' WHERE `id`='$uid';");

        try {
            $query->execute();
        } catch(\Exception $e) {
            return false;
        }

        return true;
    }


}