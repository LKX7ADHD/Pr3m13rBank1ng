<?php include_once 'include/accounts.inc.php' ?>

<!DOCTYPE html>

<html lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <?php include 'include/imports.inc.php' ?>

    <title>Pr3m13r Bank1ng | Home</title>

</head>

<body>

<?php include "include/navbar.inc.php" ?>

<!--  New Header  -->
<header class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-xl-9 col-md-11">
                <div class="hero-content">
                        <span class="d-inline-block">
                            A bank that you can trust.
                        </span>
                    <h1 class="hero__title">
                        Understand Your Finance Clearly
                    </h1>
                    <div class="hero__button">
                        <a href="register.php" title="Register Page">
                            <button class="btn">
                                Get Started
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>

<main>
    <!--  Statistics Section  -->
    <section class="stat">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-4">
                    <div class="single-fact">
                        <h2 class="title">250K+</h2>
                        <p>Customers are already seeing the benefits.</p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="single-fact">
                        <h2 class="title">100%</h2>
                        <p>Satisfaction rate from our loyal customers</p>
                    </div>
                </div>
                <div class="col-sm-12 col-md-4">
                    <div class="single-fact">
                        <h2 class="title">0%</h2>
                        <p>Loan Interest Rate. Effective Solution for your Business Needs</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--   End of Testimonial Section     -->

    <!-- Funding Section -->
    <section class="funding">
        <div class="container">
            <div class="row align-items-center">

                <div class="col-lg-6">
                    <div class="content">
                        <span class="mb-3 d-inline-block">Reliable. Effective. Efficient</span>
                        <h3>Fast Funding, like Tomorrow-fast.</h3>
                        <p>No-hassle funding. Most businesses are approved the very next day.</p>

                    </div>
                    <a href="register.php" title="Register Page">
                        <button class="btn btn__funding">
                            Fund Your Business
                        </button>
                    </a>
                </div>
                <div class="col-lg-6">
                    <div class="image-container">
                        <img src="assets/img/undraw_investment_xv9d.svg"
                             alt="An Illustration on a guy and an illustration of a workflow">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--   End of Funding Section     -->


    <!--   Start of Mobile Banking     -->
    <section class="mobile-banking">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="image-container">
                        <img src="assets/img/iphone.svg" alt="iPhone 11 Pro">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="mobile-banking__content">
                        <span class="mb-3 d-inline-block">Smart and Secure</span>
                        <h3>Miltary-Grade Encryption Banking</h3>
                        <p>Fully Layered Security Mobile Banking with smooth and efficient experience.
                            No hiccups and the smoothness of mobile banking does not compromise the security of mobile
                            banking.
                        </p>

                    </div>
                </div>

            </div>
        </div>
    </section>
    <!--   End of Mobile Banking Section     -->

    <!--   Benefits Section    -->
    <section class="benefits">
        <div class="container">
            <div class="row align-items-center flex-md-row-reverse">
                <div class="col-lg-6">
                    <div class="image-container">
                        <img src="assets/img/cards/main.svg" alt="Black Mastercard Credit Card">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="benefits__content">
                        <span class="mb-3 d-inline-block">Transfer More. Worry Less.</span>
                        <h3>Send Money All over the world with no hidden fees.</h3>
                        <p>Send money to your family, your friends regardless of where they are. Transfer money with a
                            small fee of 0.5% for anything above $10,000. This means you can worry less and transfer
                            more
                            with our services.
                        </p>

                    </div>
                    <a href="register.php" title="Register Page">
                        <button class="btn btn__benefits">
                            Get Started
                        </button>
                    </a>
                </div>

            </div>
        </div>
    </section>
    <!--    End of Benefits Section    -->

    <!--    Start of Call To Action -->
    <section class="cta-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-6">

                    <div class="cta__title text-center">
                        <h2 class="title">
                            Launch Your Business Quickly
                        </h2>
                        <p>Register to get your account started and launch your business effortlessly.</p>
                        <div class="cta__btn">
                            <a href="register.php" title="Register Page">
                                <button class="btn">
                                    Check Job Openings
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--        End of Cta Section-->

</main>


<?php include "include/footer.inc.php" ?>
</body>

</html>