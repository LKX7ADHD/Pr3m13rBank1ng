<?php
require_once 'include/accounts.inc.php';

function isFieldRequired($field) {
    // All fields are required except for First Name
    return $field !== 'fname';
}

$success = true;
$errorMessages = array();

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

$formInput = array();

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
        $errorMessages[] = 'This email (' . $formInput['email'] . ') has been registered previously.';
        $success = false;
    }
}

if (isset($formInput['username']) && isUsernameRegistered($formInput['username'])) {
    $errorMessages[] = 'This username (' . $formInput['username'] . ') has been taken.';
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

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title></title>
    <?php include 'include/imports.inc.php' ?>
</head>
<body>
<?php include "include/navbar.inc.php" ?>

<header class="jumbotron text-center">
	<h1 class="display-4">Register</h1>
</header>
<main class="container process-register px-4">
    <section class="row">
        <div class="col">
            <p class="h1">Oops!</p>
            <p class="lead">The following errors were detected:</p>
            <ul class="list-group list-group-flush">
            <?php
                foreach ($errorMessages as $errorMessage) {
                    echo '<li class="list-group-item">' . $errorMessage . '</li>';
                }
            ?>
            </ul>
            <a class="btn btn-danger btn-lg mt-4" href="register.php" role="button">Return to Register</a>
        </div>
    </section>
</main>

<?php include "include/sessionTimeout.inc.php" ?>
<?php include "include/footer.inc.php" ?>
</body>
</html>