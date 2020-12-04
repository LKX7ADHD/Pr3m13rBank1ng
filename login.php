<?php
require_once 'include/accounts.inc.php';

$user = getAuthenticatedUser();

if ($user) {
    if ($user->admin) {
        header("Location: /admin/");
        exit();
    } else {
        header("Location: /personal_banking/");
        exit();
    }
}

$errorMessages = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;
    if (isset($_POST['email']) && !empty($_POST['email'])) {
        $email = sanitiseInput($_POST['email']);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMessages[] = 'Invalid email format';
            $success = false;
        }

        if (isset($_POST['pwd'])) {
            if (!authenticateUser($email, $_POST['pwd'])) {
                $errorMessages[] = 'Email not found or password does not match';
                $success = false;
            }
        } else {
            $errorMessages[] = 'Password is required';
            $success = false;
        }
    } else {
        $errorMessages[] = 'Email is required';
        $success = false;
    }

    if ($success) {
        $user = getAuthenticatedUser();

        if (!$user->verified) {
            unset($_SESSION['user']);
            $_SESSION['user_to_verify'] = $user;

            header('Location: verify_email.php');
            exit();
        }

        if ($user->admin) {
            header("Location: /admin/");
            exit();
        } else {
            header("Location: /personal_banking/");
            exit();
        }
    }
}
?>

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
					<a href="/">
						Premier Banking
					</a>
				</div>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-7 col-xl-6">
				<div class="form-container text-center align-self-center">
					<form method="POST">
						<div class="form-title">
							<h1 class="title">Sign In</h1>
							<p>Enter your details below:</p>
						</div>
                        <?php

                        if (!empty($errorMessages)) {
                            foreach ($errorMessages as $error) {
                                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                            }
                        }

                        ?>
						<div class="form-group">
							<label for="email" class="sr-only">Email</label>
							<input type="email" class="form-control" id="email" placeholder="Enter your Email"
							       name="email" aria-label="Email" autocomplete="email" required>
						</div>
						<div class="form-group">
							<label for="pwd" class="sr-only">Password</label>
							<input type="password" class="form-control" placeholder="Enter Your Password" name="pwd"
							       aria-label="Password" id="pwd" autocomplete="current-password" required>
						</div>
						<div class="form-group">
							<button class="form-btn" type="submit">Sign In</button>
							<small class="form-text text-muted sign-up-text">Don't have an account? <a
										href="register.php" title="Register Page">Create a New
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
