<?php
require_once '../include/accounts.inc.php';
require "../include/sessiontimeout.inc.php";

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
    <title>Premier Banking | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include "../include/imports.inc.php" ?>
</head>

<body class="dashboard-body">
<?php include '../include/navbar.inc.php' ?>


<main class="container">
    <ul class="list-group">
        <?php
        if (count($accounts) == 0) {
            echo '<p class="lead">No Accounts</p>';
        } else {
            foreach ($accounts as $account) {
                echo '<li class="list-group-item"><p class="h3">' . $account->getBalanceRepresentation() . '</p>' . $account->accountName . '<p class="text-muted mt-1 mb-0">' . $account->getAccountNumberRepresentation() . '</p></li>';
            }
        }
        ?>
    </ul>
</main>
<?php include "../include/footer.inc.php" ?>
</body>

</html>
