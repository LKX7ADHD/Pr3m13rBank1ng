<?php
include_once '../include/accounts.inc.php';
include "../include/sessiontimeout.inc.php";

$user = getAuthenticatedUser();

if(!$user) {
    header('Location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title></title>
    <?php include '../include/imports.inc.php' ?>
</head>
<body>
<?php include '../include/navbar.inc.php' ?>
<header class="jumbotron text-center">
    <h1 class="display-4">Profile</h1>
</header>
<main class="container">
    <p class="h1">Hello, <?php echo $user->username; ?></p>
    <a class="btn btn-warning" href="../logout.php" role="button">Logout</a>
</main>
<?php include '../include/footer.inc.php' ?>
</body>
</html>
