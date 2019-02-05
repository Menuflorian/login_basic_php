<?php
require 'inc/function.php';
is_identificated();
if(!empty($_POST)) {
    if($_POST['password'] != $_POST['password_confirm']) {
        $_SESSION['flash']['danger'] ="Les mots de passe ne correspondes pas.";
    }elseif (empty($_POST['password']) ) {
        $_SESSION['flash']['danger'] ="Les mots de passes ne peuvent être vide";
    }else{
        $user_id = $_SESSION['auth']->Id;
        require_once 'inc/db.php';
        $password = password_hash( $_POST['password'] , PASSWORD_BCRYPT);
        $pdo-> prepare("UPDATE users SET password = ? WHERE id = ?")-> execute([$password, $user_id]);
        $_SESSION['flash']['success'] ="Votre mot de passe a bien été mis à jour.";

    }
}

require 'inc/header.php';
?>

<h1>Bienvenue <?= $_SESSION['auth']->username; ?> dans le changement de mot de passe.</h1>


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
        <input class="btn btn-primary" type="submit" value="Submit">
    </form>
</div>


<?php require 'inc/footer.php'; ?>
