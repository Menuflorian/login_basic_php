<?php

require_once 'inc/function.php';
session_start();

//Validation des champs
require_once 'inc/db.php';
if (!empty($_POST)) {

    $errors = array();

    if (empty($_POST['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $_POST['username'])) {
        $errors['username'] = "Votre pseudo n'est pas valide";
    } else {
        $req = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $req->execute([$_POST['username']]);
        $user = $req->fetch();
        if($user){
            $errors['username'] = 'Ce pseudo est déjà pris';
        }
    }

    if (empty($_POST['email']) || !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Votre email n'est pas valide";
    } else {
        $req = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $req->execute([$_POST['email']]);
        $user = $req->fetch();
        if($user){
            $errors['email'] = 'Cet email est déjà pris';
        }
    }

    if (empty($_POST['password']) || $_POST['password'] != $_POST['password_confirm']) {
        $errors['password'] = "Votre mot de passe n'est pas valide";
    }

    if(empty($errors)){
        $req = $pdo-> prepare("INSERT INTO users SET username = ?, password = ?, email = ?, confirmation_token = ?");
        $password =  password_hash( $_POST['password'] , PASSWORD_BCRYPT);
        $token = str_rand(60) ;
        $req -> execute([$_POST['username'], $password, $_POST['email'], $token]);
        $user_id = $pdo -> lastInsertId();
        mail($_POST['email'], "Confirmation de votre compte", "Afin de valider votre compte merci de cliquer sur ce lien\n\n http://localhost:8080/login/confirm.php?id=$user_id&token=$token");
        $_SESSION['flash']['success'] = 'Un e-mail de confrmation vous a été envoyé pour valider votre compte.';
        header('Location: login.php');
        exit();
    }

}
?>

<!-- Affichage des erreurs -->
<?php if (!empty($errors)):?>
    <div class="alert alert-danger">
        <p>Vous n'avez pas rempli le formulaire correctement</p>
        <ul>
            <?php foreach ($errors as $error):?>
            <li><?= $error; ?></li>
            <?php endforeach;?>
        </ul>
    </div>
<?php endif;?>

<?php require 'inc/header.php';?>

<h1> S'enregistrer </h1>


<div class="container">
    <form class="" action="" method="post">
        <div class="form-group">
            <label for="">Pseudo</label>
            <input type="text" name="username" class="form-control" placeholder="Pseudo" />
        </div>
        <div class="form-group">
            <label for="">Email</label>
            <input type="text" name="email" class="form-control" placeholder="Email" />
        </div>
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

<?php require 'inc/footer.php';?>
