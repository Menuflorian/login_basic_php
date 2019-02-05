<?php
if(!empty($_POST['email'])){
    require_once 'inc/db.php';
    require_once 'inc/function.php';
    $req = $pdo -> prepare('SELECT * FROM users WHERE email = ? AND confirmed_at IS NOT NULL');
    $req -> execute([$_POST['email']]);
    $user = $req->fetch();
    if($user){
        session_start();
        $reset_token = str_rand(60);
        $pdo -> prepare('UPDATE users set reset_token = ?, reset_at = now() WHERE id = ?')->execute([$reset_token, $user->Id]);
        $_SESSION['flash']['success'] = 'Les instruction du rappel de mot de passe vous ont été envoyées par email. ';
        mail($_POST['email'], "Réinitialisation de votre mot de passe", "Afin de réinitialisation votre compte merci de cliquer sur ce lien\n\n http://localhost:8080/login/reset.php?id={$user->Id}&token=$reset_token");
        header('Location: login.php');
        exit();
    }else{
        $_SESSION['flash']['danger'] = "Aucun compte ne correspond à cet adresse.";
    }
}
 ?>
<?php require 'inc/header.php'; ?>

<h1>Mot de passe oublié</h1>

<div class="container">
    <form class="" action="" method="post">
        <div class="form-group">
            <label for="">Email</label>
            <input type="email" name="email" class="form-control" placeholder="E-mail" />
        </div>
        <input class="btn btn-primary" type="submit" value="Réinitialisation le mot de passe.">
    </form>
</div>



<?php require 'inc/footer.php'; ?>
