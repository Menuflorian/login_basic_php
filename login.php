<?php
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

<h1>Se Connecter</h1>

<div class="container">
    <form class="" action="" method="post">
        <div class="form-group">
            <label for="">Pseudo ou email</label>
            <input type="text" name="username" class="form-control" placeholder="Pseudo" />
        </div>
        <div class="form-group">
            <label for="">Mot de passe</label>
            <input type="password" name="password" class="form-control" placeholder="Password" />
        </div>
        <ul class="list-unstyled">
        <li><a href="forget.php"><sub>J'ai oublier mon mot de passe.</sub></a></li>
        </ul>
        <input class="btn btn-primary" type="submit" value="Se connecter">
    </form>
</div>



<?php require 'inc/footer.php'; ?>
