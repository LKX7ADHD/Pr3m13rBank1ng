<?php
require_once 'include/accounts.inc.php';

$user = getAuthenticatedUser();

if ($user) {
    if ($user->admin) {
        header("Location: /admin/");
    } else {
        header("Location: /personal_banking/");
    }
}

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
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Premier Banking | Register</title>
    <?php include 'include/imports.inc.php' ?>
</head>

<body id="register-body">


<main id="register">
	<section class="container d-flex flex-column justify-content-start h-100">
		<div class="row justify-content-center">
			<div class="col-lg-7 col-xl-6">
				<div class="brand text-center mt-3">
					<a href="index.php">
						Premier Banking
					</a>
				</div>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-7 col-xl-6">
				<div class="form-container text-center align-self-center">
					<form action="process_register.php" method="POST">

						<div class="form-title">
							<h1 class="title">Register</h1>
							<p>Enter your account details below</p>
						</div>

						<div class="form-group">
							<label for="username" class="text-muted">Username</label>
							<input class="form-control" type="text" id="username" name="username" aria-label="Username"
							       placeholder="Enter username"
							       required/>
						</div>

						<div class="form-group">
							<label for="fname" class="text-muted">First Name</label>
							<input class="form-control" type="text" id="fname" name="fname" maxlength="45"
							       aria-label="First Name"
							       placeholder="Enter first name"/>
						</div>

						<div class="form-group">
							<label for="lname" class="text-muted">Last Name</label>
							<input class="form-control" type="text" id="lname" name="lname" maxlength="45"
							       aria-label="Last Name" placeholder="Enter last name"
							       required/>
						</div>

						<div class="form-group">
                            <label for="email" class="text-muted">Email</label>
							<input class="form-control" type="email" id="email" name="email" aria-label="Email"
							       placeholder="Enter email"
							       pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" required/>
						</div>

						<div class="form-group">
                            <label for="pwd_new" class="text-muted">Password</label>
							<input class="form-control" type="password" id="pwd_new" name="pwd" aria-label="Password"
							       placeholder="Enter password" required/>
						</div>

                        <div class="border rounded p-2 m-4">
                            <p class="lead">Password requirements</p>
                            <ul class="pwd-requirement-list">
                                <li class="pwd-requirement">Minimum 8 characters</li>
                                <li class="pwd-requirement">At least one number</li>
                                <li class="pwd-requirement">At least one uppercase letter</li>
                                <li class="pwd-requirement">At least one special character</li>
                            </ul>
                        </div>

						<div class="form-group">
                            <label for="pwd_confirm" class="text-muted">Confirm Password</label>
							<input class="form-control" type="password" id="pwd_confirm"
							       aria-label="Confirmation of Password" name="pwd_confirm"
							       placeholder="Confirm password" required/>
						</div>

						<div class="form-check" id="form-agree">
							<label>
								<input type="checkbox" name="agree" aria-label="Checkbox" required/>
								Agree to Premier Banking's terms and conditions.
							</label>
						</div>


						<div class="form-group">
							<button class="form-btn" type="submit">Register</button>
							<small class="form-text text-muted sign-up-text">Already with us? <a href="login.php">Click
									Here to Login</a></small>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

</main>


</body>

</html>