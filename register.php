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

function isFieldRequired($field) {
    // All fields are required except for First Name
    return $field !== 'fname';
}

$errorMessages = array();
$formInput = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $success = true;

    if (!isset($_POST['agree'])) {
        $errorMessages[] = 'Please agree to the terms and conditions.';
        $success = false;
    }

    $fields = array(
        'fname' => 'First name',
        'lname' => 'Last name',
        'email' => 'Email',
        'username' => 'Username'
    );

    foreach ($fields as $field => $fieldLabel) {
        if (isset($_POST[$field]) && !empty($_POST[$field])) {
            $formInput[$field] = sanitiseInput($_POST[$field]);
        } elseif (isFieldRequired($field)) {
            $errorMessages[] = $fieldLabel . ' is required.';
            $success = false;
        } else {
            $formInput[$field] = '';
        }
    }

    if (isset($formInput['email'])) {
        if (!filter_var($formInput['email'], FILTER_VALIDATE_EMAIL)) {
            $errorMessages[] = 'Invalid email format';
            $success = false;
        } elseif (isEmailRegistered($formInput['email'])) {
            $errorMessages[] = 'The email (' . $formInput['email'] . ') has been registered previously.';
            $success = false;
        }
    }

    if (isset($formInput['username']) && isUsernameRegistered($formInput['username'])) {
        $errorMessages[] = 'The username (' . $formInput['username'] . ') has been taken.';
        $success = false;
    }

    if (isset($_POST['pwd'])) {
        if (strlen($_POST['pwd']) < 8) {
            $errorMessages[] = 'Passwords should be at least 8 characters long.';
            $success = false;
        }

        if (!preg_match('/\d/', $_POST['pwd'])) {
            $errorMessages[] = 'Passwords should contain at least one number.';
            $success = false;
        }

        if (!preg_match('/[A-Z]/', $_POST['pwd'])) {
            $errorMessages[] = 'Passwords should contain at least one uppercase letter.';
            $success = false;
        }

        if (!preg_match('/[!@#\$%^&*)(+=._-]/', $_POST['pwd'])) {
            $errorMessages[] = 'Passwords should contain at least one special character.';
            $success = false;
        }
    }

    if (isset($_POST['pwd']) && isset($_POST['pwd_confirm'])) {
        if ($_POST['pwd'] !== $_POST['pwd_confirm']) {
            $errorMessages[] = 'Passwords do not match.';
            $success = false;
        } else {
            $formInput['hashed_password'] = password_hash($_POST['pwd'], PASSWORD_DEFAULT);
        }
    } else {
        $errorMessages[] = 'Password is required';
        $success = false;
    }

    if ($success) {
        $displayName = (empty($formInput['fname']) ? '' : $formInput['fname'] . ' ') . $formInput['lname'];

        $user = new User();
        $user->firstName = $formInput['fname'];
        $user->lastName = $formInput['lname'];
        $user->email = $formInput['email'];
        $user->username = $formInput['username'];
        $user->admin = false;

        registerUser($user, $formInput['hashed_password']);
        $_SESSION['user_to_verify'] = $user;
        $_SESSION['request_code'] = true;

        header('Location: verify_email.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description"
	      content="Register under Premier Banking to manage all your financial needs a from single dashboard.">
	<title>Premier Banking | Register</title>
    <?php include 'include/imports.inc.php' ?>
</head>

<body id="register-body">

<main id="register">
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
							<h1 class="title">Register</h1>
							<p>Enter your account details below:</p>
						</div>

                        <?php

                        if (!empty($errorMessages)) {
                            foreach ($errorMessages as $error) {
                                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                            }
                        }

                        ?>

						<div class="form-group">
							<label for="username" class="text-muted">Username</label>
							<input class="form-control" type="text" id="username" name="username" aria-label="Username"
							       placeholder="Enter username" autocomplete="username"
							       value="<?php if (isset($formInput['username'])) echo $formInput['username'] ?>"
							       required/>
						</div>

						<div class="form-group">
							<label for="fname" class="text-muted">First Name</label>
							<input class="form-control" type="text" id="fname" name="fname" maxlength="45"
							       aria-label="First Name" placeholder="Enter first name" autocomplete="given-name"
							       value="<?php if (isset($formInput['fname'])) echo $formInput['fname'] ?>"/>
						</div>

						<div class="form-group">
							<label for="lname" class="text-muted">Last Name</label>
							<input class="form-control" type="text" id="lname" name="lname" maxlength="45"
							       aria-label="Last Name" placeholder="Enter last name" autocomplete="family-name"
							       value="<?php if (isset($formInput['lname'])) echo $formInput['lname'] ?>" required/>
						</div>

						<div class="form-group">
							<label for="email" class="text-muted">Email</label>
							<input class="form-control" type="email" id="email" name="email" aria-label="Email"
							       placeholder="Enter email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"
							       autocomplete="email"
							       value="<?php if (isset($formInput['email'])) echo $formInput['email'] ?>" required/>
						</div>

						<div class="form-group">
							<label for="pwd_new" class="text-muted">Password</label>
							<input class="form-control" type="password" id="pwd_new" name="pwd" aria-label="Password"
							       placeholder="Enter password" autocomplete="new-password" required/>
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
							       placeholder="Enter password again" autocomplete="new-password" required/>
						</div>

						<div class="form-check" id="form-agree">
							<input class="form-check-input" type="checkbox" name="agree" aria-label="Checkbox"
							       id="agree" required/>
							<label class="form-check-label" for="agree">Agree to Premier Banking's terms and
								conditions.</label>
						</div>

						<div class="form-group mt-4">
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

<?php include "include/sessionTimeout.inc.php" ?>
</body>
</html>
