<?php
include_once 'include/accounts.inc.php';
logOut();
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
    <?php include 'include/imports.inc.php' ?>
</head>
<body>
<?php include "include/navbar.inc.php" ?>

<header class="jumbotron text-center">
    <h1 class="display-4">Logout</h1>
</header>
<main class="container">
    <p class="h1">Logout success</p>
    <a class="btn btn-success" href="/" role="button">Return to Home</a>
</main>

<?php include "include/footer.inc.php" ?>
</body>

</html>
