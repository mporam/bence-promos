<?php

/**************** DB CONFIG *****************/
//require($_SERVER['DOCUMENT_ROOT'] . '/sql/db_con.php');
$con = $data['db'];

/**************** VERIFICATION *****************/
//session_start();
//
//if(empty($_SESSION)) {
//    session_destroy();
//    header("Location: /login/");
//}
//
//$email= $_SESSION['email'];
//$password = $_SESSION['password'];
//
//$query = $con->prepare("SELECT * FROM users WHERE `email` = '$email' AND `password` = '$password'");
//$query -> execute();
//$check = $query->fetch(PDO::FETCH_ASSOC);
//
//if (empty($check)) {
//    session_destroy();
//    header("Location: /login/?login=failed");
//}

/**************** NOTIFICATIONS *****************/

//if ($_SESSION['access'] !== 5) {
//    header('Location: /account/');
//}

$query = $con->prepare("SELECT * FROM users LEFT JOIN regions ON users.region = regions.r_id;");
$query -> execute();
$users = $query->fetchAll();

$query = $con->prepare("SELECT * FROM regions;");
$query -> execute();
$regions = $query->fetchAll();

?>
