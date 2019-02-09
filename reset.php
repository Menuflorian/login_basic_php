<?php

if (isset($_GET['id']) && issset($_GET['token'])) {
    require 'inc/db.php';
    $req = $pdo->prepare('SELECT * FROM user')
}else {
    header('Location: login.php');
    exit();
}


if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){
    require_once 'inc/db.php';
    require_once 'inc/function.php';
    $req = $pdo -> prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
    $req -> execute(['username' => $_POST['username']]);
    $user = $req->fetch();
    if(password_verify($_POST['password'], $user->password )){
        session_start();
        $_SESSION['auth'] = $user;
        $_SESSION['flash']['success'] = 'Vous êtes maintenant connecter.';
        header('Location: account.php');
        exit();
    }else{
        $_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrecte.';
    }
}
 ?>
<?php require 'inc/header.php'; ?>

<h1>Réinitialiser mon mot de passe.</h1>

<div class="container">
    <form class="" action="" method="post">
        <div class="form-group">
            <label for="">Mot de passe</label>
            <input type="password" name="password" class="form-control" placeholder="Password" />
        </div>
        <div class="form-group">
            <label for="">Confirmer votre mot de passe</label>
            <input type="password" name="password_confirm" class="form-control" placeholder="Confirm" />
        </div>
        <input class="btn btn-primary" type="submit" value="Réinitialiser mon mot de passe.">
    </form>
</div>



<?php require 'inc/footer.php'; ?>
