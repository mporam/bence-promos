<?php

namespace Bence;


class Notifications
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getNotificationById($notificationId)
    {
        $query = $this->db->prepare('
            SELECT * FROM `email_log`
            JOIN email_user_sent_status ON email_id = email_log.id
                WHERE `email_id` = ?;
        ');

        $query->execute(array($notificationId));
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getRecipientsByNotificationId($notificationId)
    {
        $query = $this->db->prepare('
            SELECT user_id, email, sent_successfully
            FROM email_user_sent_status
            JOIN users on user_id = users.id
                WHERE `email_id` = ?;
        ');

        $query->execute(array($notificationId));
        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }
}
