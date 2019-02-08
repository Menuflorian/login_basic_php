<?php
require 'inc/function.php';

if( isset($_GET['id']) && isset($_GET['token'])) {
    require 'inc/db.php';
    $req = $pdo-> prepare('SELECT * FROM users WHERE Id = ? AND reset_token IS NOT NULL AND reset_token = ? AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)');
    $req-> execute([$_GET['id'], $_GET['token']]);
    $user = $req ->fetch();
    if($user){
        if(!empty($_POST['password']) && $_POST['password'] == $_POST['password_confirm']){
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $pdo -> prepare('UPDATE users SET password = ?, reset_at = NULL, reset_token = NULL')->execute([$password]);
            session_start();
            $_SESSION['flash']['Success']="Votre mot de passe à bien été modifier.";
            $_SESSION['auth'] = $user;
            header('Location: account.php');
            exit();
        }
    }else {
        session_start();
        $_SESSION['Flash']['danger'] = "Ce token n'est pas valide";
        header('Location: login.php');
        exit();

    }

}else{
    header('Location: login.php');
    exit();
}

 ?>
<?php require 'inc/header.php'; ?>

<h1>Reinitialiser le mot de passe</h1>

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
        <input class="btn btn-primary" type="submit" value="Se connecter">
    </form>
</div>



<?php require 'inc/footer.php'; ?>
