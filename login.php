<?php require_once 'include/accounts.inc.php' ?>

<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Premier Banking | Login</title>
    <?php include 'include/imports.inc.php' ?>
</head>
<body id="login-body">


<main id="login" class="h-100">
	<section class="container d-flex flex-column justify-content-start h-100">
		<div class="row justify-content-center">
			<div class="col-lg-7 col-xl-6">
				<div class="brand text-center mt-5">
					<a href="index.php">
						Premier Banking
					</a>
				</div>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-7 col-xl-6">
				<div class="form-container text-center align-self-center">
					<form action="process_login.php" method="POST">
						<div class="form-title">
							<h1 class="title">Sign In</h1>
							<p>Enter your account details below:</p>
						</div>
						<div class="form-group">
							<label for="email" class="text-muted">Email</label>
							<input type="email" class="form-control" id="email" placeholder="Enter your Email"
							       name="email" aria-label="Email" required>
						</div>
						<div class="form-group">
							<label for="pwd" class="text-muted">Password</label>
							<input type="password" class="form-control" placeholder="Enter Your Password" name="pwd"
							       aria-label="Password" required>
						</div>
						<div class="form-group">
							<button class="form-btn" type="submit">Sign In</button>
							<small class="form-text text-muted sign-up-text">Don't have an account? <a
										href="register.php" title="Register Page">Click here to Create a New
									Account.</a></small>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

</main>

<?php include "include/sessionTimeout.inc.php" ?>
</body>
</html>
