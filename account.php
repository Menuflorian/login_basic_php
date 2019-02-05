<?php
require 'inc/function.php';
is_identificated();
require 'inc/header.php';
?>

<h1>Bienvenue <?= $_SESSION['auth']->username; ?></h1>

<?php require 'inc/footer.php'; ?>
