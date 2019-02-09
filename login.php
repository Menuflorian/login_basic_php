<?php
require_once 'inc/function.php';
reconnection_auto();
if(isset($_SESSION['auth'])) {
    header('Location: account.php');
}
if(!empty($_POST) && !empty($_POST['username']) && !empty($_POST['password'])){

    $req = $pdo -> prepare('SELECT * FROM users WHERE (username = :username OR email = :username) AND confirmed_at IS NOT NULL');
    $req -> execute(['username' => $_POST['username']]);
    $user = $req->fetch();
    if(password_verify($_POST['password'], $user->password )){
        session_start();
        $_SESSION['auth'] = $user;
        $_SESSION['flash']['success'] = 'Vous êtes maintenant connecter.';
        if($_POST['remember']){
            $remember_token = str_rand(250);

            $pdo->prepare('UPDATE users SET remember_token = ? WHERE id = ?')->execute([$remember_token, $user->Id]);
            setcookie('remember', $user->Id . "==" . $remember_token . sha1($user->Id . 'chienchatlapin'),time() + 60 * 60 * 10);
        }
        header('Location: account.php');
        exit();
    }else{
        session_start();
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
        <div class="form-check">
          <input type="checkbox" class="form-check-input" name="remember">
          <label class="form-check-label" >Se souvenir de moi.</label>
        </div>
        <input class="btn btn-primary" type="submit" value="Se connecter">
    </form>
</div>



<?php require 'inc/footer.php'; ?>
