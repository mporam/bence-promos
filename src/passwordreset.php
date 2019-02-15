<?php
$email = $post['email'];

$query = $this->db->prepare("SELECT * FROM users WHERE `email` = '$email';");
$query -> execute();
$user = $query->fetch();

$sent = false;

if (!empty($user)) {

    require __DIR__ . '/classes/PHPMailer.php';

    $resetKey = sha1($user['email'] . time());
    $query = $this->db->prepare("UPDATE users SET pwreset='$resetKey' WHERE `email`='$email';");
    $query->execute();

    $mail = new PHPMailer();
    $mail->IsSMTP();  // telling the class to use SMTP
    $mail->Host = "mail.bencerewards.co.uk"; // SMTP server

    $message = "Hi " . $user['name'] . ",

You have requested a password reset, click the link below to reset your password. If you did not request this please discard this email.

" . $_SERVER['HTTP_HOST'] . "/login/reset?user=" . $resetKey . "

Many Thanks,

Bence Rewards Team";

    $mail->SetFrom("no-reply@bencerewards.co.uk", 'Bence Rewards');
    $mail->AddAddress($email);
    $mail->Subject = 'Bence Rewards Password Reset';
    $mail->Body = $message;
    $mail->WordWrap = 78;

    $args['sent'] = $mail->Send();
}