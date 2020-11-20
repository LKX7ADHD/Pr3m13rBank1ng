<?php include_once 'include/accounts.inc.php' ?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Pr3m13r Bank1ng | Login</title>
    <?php include 'include/imports.inc.php' ?>
</head>
<body>

<?php include "include/navbar.inc.php" ?>
<!---->
<!--<header class="jumbotron jumbotron-fluid text-center">-->
<!--    <div class="container">-->
<!--        <h1 class="display-4">Login</h1>-->
<!--    </div>-->
<!--</header>-->
<!--<main class="container">-->
<!--    <p>If you do not have an account, please <a href="register.php">open one</a>.</p>-->
<!--    <form action="process_login.php" method="POST">-->
<!---->
<!--        <div class="form-group">-->
<!--            <label for="email">Email</label>-->
<!--            <input class="form-control" type="email" id="email"-->
<!--                   name="email" placeholder="Enter email"-->
<!--                   required/>-->
<!--        </div>-->
<!---->
<!--        <div class="form-group">-->
<!--            <label for="pwd">Password</label>-->
<!--            <input class="form-control" type="password" id="pwd"-->
<!--                   name="pwd" placeholder="Enter password" required/>-->
<!--        </div>-->
<!---->
<!--        <div class="form-group">-->
<!--            <button class="btn btn-primary" type="submit">Submit</button>-->
<!--        </div>-->
<!--    </form>-->
<!--</main>-->

<main class="login">
    <section class="container">
        <div class="row d-flex align-items-center justify-content-center">
            <div class="col-md-6">
                <div class="login__image">
                    <img src="assets/img/undraw_mobile_login_ikmv.svg" alt="SVG on Login">
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <form class="form__container" action="process_login.php" method="POST">
                    <p>If you do not have an account, please <a href="register.php">open one</a>.</p>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input type="email" class="form-control" id="email" aria-describedby="emailHelp">
                        <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
                    </div>
                    <div class="form-group">
                        <label for="pwd">Password</label>
                        <input type="password" class="form-control" id="pwd">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </section>
</main>

<?php include "include/footer.inc.php" ?>
</body>
</html>
