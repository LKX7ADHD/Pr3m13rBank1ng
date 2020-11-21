<?php include_once 'include/accounts.inc.php' ?>

<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
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

    <!-- Start of Header -->
    <header class="hero">
<!--        <div class="">-->
            <div class="container hero__content">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="banner__content mb-2">
                            <h1>Get an extensive view of your cash flow.</h1>
                            <p>Automate your finance and increase your savings with our extensive dashboard. Protect Your Hard-Earned Money.</p>
                        </div>
                        <a href="register.php">
                        <button class="banner__button btn btn-primary my-5 py-3 px-4">
                            Get Started
                        </button>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <div class="banner__image">
                            <img src="assets/img/undraw_finance_0bdk.svg" alt="SVG on Finance" class="img-fluid">
                        </div>
                    </div>
                </div>
<!--            </div>-->
        </div>
    </header>

    <!-- End of Header -->

    <!-- Start of Main -->

    <main>

        <!-- Start of Grow Area -->
        <section class="grow pt-5 pb-5">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-5 col-lg-6">
                        <div class="grow__image">
                            <img src="assets/img/undraw_business_shop_qw5t.svg" alt="An SVG of a man pointing to a bank">
                        </div>
                    </div>
                    <div class="col-md-7 col-lg-6">
                        <div class="grow__content">
                            <span>Help your Business Grow</span>
                            <h3>Acquire New Business Opportunities Through Our Financial Solutions and Strategies.</h3>
                            <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Quis ipsum suspendisse ultrices gravida. Risus commodo
                                viverra maecenas accumsan lacus vel facilisis.</p>
                            <div class="grow__inner-content">
                                <div class="number mr-3">
                                    <span>1</span>
                                </div>
                                <div class="grow__container">
                                    <h4>Open An Account In Minutes</h4>
                                    <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua.</p>
                                </div>

                            </div>
                            <div class="grow__inner-content">
                                <div class="number mr-3">
                                    <span>2</span>
                                </div>
                                <div class="grow__container">
                                    <h4>0% Interest Rate</h4>
                                    <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua.</p>
                                </div>
                            </div>
                            <div class="grow__inner-content">
                                <div class="number mr-3">
                                    <span>3</span>
                                </div>
                                <div class="grow__container">
                                    <h4>Financial Life at your fingertips</h4>
                                    <p>Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor
                                        incididunt ut labore et dolore magna aliqua.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- End of Grow Area -->

        <!--  -->

        <section class="funding">
            <div class="container">
                <div class="row align-items-center">

                    <div class="col-lg-6">
                        <div class="content">
                            <span class="mb-3 d-inline-block">Reliable. Effective. Efficient</span>
                            <h3>Fast Funding, like Tomorrow-fast.</h3>
                            <p>No-hassle funding. Most businesses are approved the very next day.</p>

                        </div>
                        <button class="btn btn-primary py-3 px-4 btn__funding">
                            Fund Your Business Now
                        </button>
                    </div>
                    <div class="col-lg-6">
                        <div class="image-container">
                            <img src="assets/img/undraw_investment_xv9d.svg" alt="#">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Start Testimonial Area -->
        <section class="testimonial">
            <div class="container">
                <div class="testimonial__container">
                    <span>Why choose us</span>
                    <h2>Our bank has been providing services to its customers for almost 25 years.</h2>
                </div>

                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="single-fun-fact">
                            <div class="icon">
                                <i class="flaticon-positive-vote"></i>
                            </div>

                            <h3>
                                946372
                            </h3>
                            <p>Happy customers</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="single-fun-fact">
                            <div class="icon">
                                <i class="flaticon-confetti"></i>
                            </div>

                            <h3>
                                25
                            </h3>
                            <p>Years in banking</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="single-fun-fact">
                            <div class="icon">
                                <i class="flaticon-bank"></i>
                            </div>

                            <h3>
                                2631
                            </h3>
                            <p>Our branches</p>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-6">
                        <div class="single-fun-fact">
                            <div class="icon">
                                <i class="flaticon-success"></i>
                            </div>

                            <h3>
                                75263
                            </h3>
                            <p>Successfully works</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Testimonial Area -->


    </main>

    <?php include "include/footer.inc.php" ?>
</body>

</html>