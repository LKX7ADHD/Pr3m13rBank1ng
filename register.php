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
        <h1 class="display-4">Register Account</h1>
    </div>
</header>
<main class="container">
    <p>For existing account holders, please <a href="login.php">login</a> instead.</p>
    <form action="process_register.php" method="POST">
        <div class="form-group">
            <label for="username">Username</label>
            <input class="form-control" type="text" id="username"
                   name="username" placeholder="Enter username"
                   required/>
        </div>

        <div class="form-group">
            <label for="fname">First Name</label>
            <input class="form-control" type="text" id="fname"
                   name="fname" maxlength="45"
                   placeholder="Enter first name"/>
        </div>

        <div class="form-group">
            <label for="lname">Last Name</label>
            <input class="form-control" type="text" id="lname"
                   name="lname" maxlength="45"
                   placeholder="Enter last name" required/>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control" type="email" id="email"
                   name="email" placeholder="Enter email"
                   pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
                   required/>
        </div>

        <div class="form-group">
            <label for="pwd">Password</label>
            <input class="form-control" type="password" id="pwd"
                   name="pwd" placeholder="Enter password" required/>
        </div>

        <div class="form-group">
            <label for="pwd_confirm">Confirm password</label>
            <input class="form-control" type="password" id="pwd_confirm"
                   name="pwd_confirm" placeholder="Confirm password"
                   required/>
        </div>

        <div class="form-check">
            <label>
                <input type="checkbox" name="agree" required/>
                Agree to Premier Banking's terms and conditions.
            </label>
        </div>

        <div class="form-group">
            <button class="btn btn-primary" type="submit">Register</button>
        </div>
    </form>
</main>

<?php include "include/footer.inc.php" ?>
</body>
</html>
