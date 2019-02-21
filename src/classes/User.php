<?php

namespace Bence;

class User
{
    private $db;

    const COOKIENAME = 'benceLogin';

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function attemptLogin() {
        if (!empty($_COOKIE[$this::COOKIENAME])) {
            $auth = json_decode($_COOKIE[$this::COOKIENAME]);

            $email = $auth->email;
            $password = $auth->password;

            return $this->storeUserWithHash($email, $password);
        }
        return false;
    }

    public function login($email, $password, $remember) {

        if ($this->verify($email, $password)) {

            if (!empty($remember)) {
                    $value = array(
                        'email' => $_SESSION['email'],
                        'password' => $_SESSION['password']
                    );
                    $expiry = time()+(365 * 24 * 60 * 60);
                    setcookie($this::COOKIENAME, json_encode($value), $expiry, '/');
            }

            return true;
        }
        return false;
    }

    public function verify($email, $password) {
        $query = $this->db->prepare("SELECT `salt` FROM users WHERE `email` = '$email'");
        $query->execute();
        $salt = $query->fetchColumn();

        if (!empty($salt)) {
            $hash = sha1($password . $salt);
            return $this->storeUserWithHash($email, $hash);
        }
        return false;
    }

    private function storeUserWithHash($email, $hash) {
        $query = $this->db->prepare("
                SELECT * FROM users
                  LEFT JOIN regions ON users.region=regions.r_id
                  WHERE users.email = '$email'
                    AND users.password = '$hash'"
        );
        $query->execute();
        $user = $query->fetch(\PDO::FETCH_ASSOC);

        if (!empty($user)) {
            $_SESSION = $user; // store user details in session
            $_SESSION['loggedIn'] = true;

            $_SESSION['permissions'] = $this->getUserPermissions($user['id']);

            return true;
        }
        return false;
    }

    public function getUserWithHash($email, $hash) {
        $query = $this->db->prepare("
                SELECT * FROM users
                  LEFT JOIN regions ON users.region=regions.r_id
                  LEFT JOIN `promotions2015` ON `users`.`promo`=`promotions2015`.`id`
                  WHERE users.email = '$email'
                    AND users.password = '$hash';
        ");
        $query->execute();
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function getUserWithId($uid) {
        $query = $this->db->prepare("
                SELECT *, `users`.`id` AS 'id' FROM `users`
                  LEFT JOIN regions ON `users`.`region`=`regions`.`r_id`
                  LEFT JOIN `promotions2015` ON `users`.`promo`=`promotions2015`.`id`
                  WHERE users.id = '$uid';
        ");
        $query->execute();
        return $query->fetch(\PDO::FETCH_ASSOC);
    }

    public function getUserPermissions($uid) {
        $query = $this->db->prepare("
            SELECT `childUser` FROM `userPermissions`
              LEFT JOIN `users` ON `users`.`id` = `userPermissions`.`childUser`
              WHERE `userId` = '$uid'
              ORDER BY `users`.`name`;
        ");
        $query->execute();
        return $query->fetchAll(\PDO::FETCH_COLUMN);
    }

    public function validateReset($resetId) {
        $result = false;
        $query = $this->db->prepare("SELECT * FROM users WHERE `pwreset` = '$resetId'");
        $query->execute();
        $user = $query->fetch();

        if (!empty($user)) {
            $result = true;
        }
        return $result;
    }

    public function resetPassword($resetId, $password) {
        $query = $this->db->prepare("SELECT * FROM users WHERE `pwreset` = '$resetId'");
        $query->execute();
        $user = $query->fetch();
        $password = sha1($password . $user['salt']);
        $id = $user['id'];

        $query = $this->db->prepare("UPDATE users SET pwreset=null, password='$password' WHERE `id`='$id';");
        return $query->execute();
    }

    public function getTotalUsers() {
        $query = $this->db->prepare("SELECT count(id) FROM users WHERE `access` = 1");
        $query->execute();
        return $query->fetch()[0];
    }
	
    public function getUsersReachedTier($tier) {
        $query = $this->db->prepare("
            SELECT `users`.*, `users`.`id` AS 'id'
             FROM `users`
             LEFT JOIN `expenses2015` ON users.accNo = expenses2015.accNo
             LEFT JOIN `limits2015` ON users.accNo = limits2015.accNo
                WHERE `expenses2015`.`total` > `limits2015`.`t" . $tier . "limit`
				AND `limits2015`.`t" . $tier . "limit` > 0;
        ");

        $query->execute();
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function update($uid, $user) {
        $name = $user['name'];
        $email = $user['email'];
        $company = $user['company'];
        $address1 = $user['address1'];
        $address2 = $user['address2'];
        $address3 = $user['address3'];
        $address4 = (empty($user['address4']) ? null : $user['address4']);
        $postcode = $user['postcode'];
        $phone = $user['phone'];
        $mobile = (empty($user['mobile']) ? null : $user['mobile']);

        $query = $this->db->prepare("UPDATE users SET `name`='$name', `email`='$email', `company`='$company',
`address1`='$address1', `address2`='$address2', `address3`='$address3', `address4`='$address4', `postcode`='$postcode',
`phone`='$phone', `mobile`='$mobile' WHERE `id`='$uid'; ");

        $return = $query->execute();

        //update session with new data
        $query = $this->db->prepare("SELECT * FROM users LEFT JOIN regions ON users.region=regions.r_id WHERE users.id = '$uid'");
        $query->execute();
        $result = $query->fetch(\PDO::FETCH_ASSOC);
        $_SESSION = array_merge($_SESSION, $result);

        return $return;
    }

    public function sortUsers($users) {

        usort($users, array('\Bence\User','compareByCompanyName'));

        return $users;
    }

    private static function compareByCompanyName($a, $b) {
        return strcmp($a["company"], $b["company"]);
    }


}