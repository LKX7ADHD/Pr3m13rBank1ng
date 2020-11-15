<?php
include_once '../include/accounts.inc.php';
include "sessiontimeout.inc.php";

$user = getAuthenticatedUser();
$accounts = getAccounts($user);


if (!$user) {
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
    <h1 class="display-4">Transfer</h1>
</header>
<main class="container">
    <div class="dropdown">
        <button type="button" class="btn btn-light dropdown-toggle" id="receiverDropdownButton"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Select account
        </button>
        <div class="dropdown-menu" aria-labelledby="receiverDropdownButton">
            <span class="dropdown-header">My accounts</span>
            <a class="dropdown-item" href="#">One of my Accounts</a>
            <a class="dropdown-item" href="#">Another Account</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Other account</a>
        </div>
    </div>
</main>
<?php include '../include/footer.inc.php' ?>
</body>
</html>
