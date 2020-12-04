<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Premier Banking is a website where you can safely bank. You will be presented with different accounts for your needs and an extensive dashboard.">
    <?php include 'include/imports.inc.php' ?>
    <title>Premier Banking | Home</title>
</head>
<body>

<?php include "include/navbar.inc.php" ?>


<!--  New Header  -->
<header class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-5 col-md-3">
                <img src="./assets/img/undraw_page_not_found_su7k.svg" alt="An Illustration of a woman sitting on top of a 404 text.">
            </div>
            <div class="col-lg-8 col-xl-7 col-md-9">
                <div class="hero-content">

                    <h1 class="hero__title">
                        Uh Oh, that page couldn't be found.
                    </h1>
                    <p>But don't worry. We will get you there!</p>
                    <div class="hero__button">
                        <a href="register.php" title="Register Page" class="btn">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>



<?php include "include/sessionTimeout.inc.php" ?>
<?php include "include/footer.inc.php" ?>
</body>

</html>

