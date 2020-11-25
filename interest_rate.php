<?php require_once 'include/accounts.inc.php' ?>

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
	<div class="hero__content">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="banner__content mb-2">
						<h1>Interest Rate Calculator</h1>
						<p>Calculate your interest rate using compound interest rates!</p>
					</div>
					<button class="banner__button btn btn-success my-2 my-sm-0 py-3 px-4">
						Get Started
					</button>
				</div>
			</div>
		</div>
	</div>
</header>

<!-- End of Header -->

<!-- Start of Main -->

<main>
	<section class="container">
		<div class="col-lg-6 mt-5">
			<form>
				<div class="form-group">
					<label for="exampleInputEmail1">Email address</label>
					<input type="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
					       placeholder="Enter email">
					<small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone
						else.</small>
				</div>
				<div class="form-group">
					<label for="exampleInputPassword1">Password</label>
					<input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
				</div>
				<div class="form-check">
					<input type="checkbox" class="form-check-input" id="exampleCheck1">
					<label class="form-check-label" for="exampleCheck1">Check me out</label>
				</div>
				<button type="submit" class="btn btn-primary">Submit</button>
			</form>
		</div>
	</section>
</main>

<?php include "include/footer.inc.php" ?>
</body>

</html>