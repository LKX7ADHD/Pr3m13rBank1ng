<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="403 page">
    <meta name="robots" content="follow, noarchive, noindex">
    <?php include 'include/imports.inc.php' ?>
    <title>Premier Banking | 401</title>
</head>
<body>

<?php include "include/navbar.inc.php" ?>

<!--  New Header  -->
<header class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-5 col-md-3">
                <img class="error-img" src="/assets/img/undraw_page_not_found_su7k.svg"
                     alt="An Illustration of a woman sitting on top of a 404 text.">
            </div>
            <div class="col-lg-8 col-xl-7 col-md-9">
                <div class="hero-content">

                    <h1 class="hero__title">
                        Uh oh, you're not authorised.
                    </h1>
                    <p>Did you mean to login? </p>
                    <a href="/login.php" title="Login" class="btn btn__primary btn-lg">Login</a>
                    <a href="/" title="Homepage" class="btn btn__primary btn-lg">Return to Homepage</a>
                </div>
            </div>
        </div>
    </div>
</header>

<?php include "include/sessionTimeout.inc.php" ?>
<?php include "include/footer.inc.php" ?>
</body>

</html>
