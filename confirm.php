<?php
require 'inc/function.php';
require 'inc/db.php';

$user_id = $_GET['id'];
$token = $_GET['token'];
$req = $pdo -> prepare('SELECT confirmation_token FROM users WHERE id = ?');
$req -> execute([$user_id]);
$user = $req -> fetch();
    session_start();
if($user && $user->confirmation_token == $token){
    $pdo->prepare('UPDATE users SET confirmation_token = NULL, Confirmed_at = NOW() WHERE id= ?')->execute([$user_id]);
    $req = $pdo -> prepare('SELECT * FROM users WHERE id = ?');
    $req -> execute([$user_id]);
    $user = $req -> fetchall();
    $_SESSION['auth'] = $user;
    $_SESSION['flash']['success'] = 'Votre compte a bien été validé.';
    header('Location: account.php');
}else{
    $_SESSION['flash']['danger']= "Ce token n'est plus valide";
    header('Location: login.php');
}
