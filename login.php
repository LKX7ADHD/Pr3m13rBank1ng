<?php include_once 'include/accounts.inc.php' ?>

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

<header class="jumbotron jumbotron-fluid text-center">
    <div class="container">
        <h1 class="display-4">Login</h1>
    </div>
</header>
<main class="container">
    <p>If you do not have an account, please <a href="register.php">open one</a>.</p>
    <form action="process_login.php" method="POST">

        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" type="email" id="email"
                   name="email" placeholder="Enter email"
                   required/>
        </div>

        <div class="form-group">
            <label for="pwd">Password</label>
            <input class="form-control" type="password" id="pwd"
                   name="pwd" placeholder="Enter password" required/>
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Submit</button>
        </div>
    </form>
</main>

<?php include "include/footer.inc.php" ?>
</body>
</html>
