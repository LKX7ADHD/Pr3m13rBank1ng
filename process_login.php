<?php

include_once 'include/accounts.inc.php';


$success = true;
$errorMessages = array();


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
    <h1 class="display-4">Login</h1>
</header>
<main class="container">
    <?php
    if ($success) {
        echo '<p class="h1">Login successful!</p>';
        echo '<p class="lead">Welcome back, ' . getAuthenticatedUser()->username . '.</p>';
        echo '<a class="btn btn-success" href="/personal_banking/dashboard.php" role="button">Continue</a>';
    } else {
        echo '<p class="h1">Oops</p>';
        echo '<p class="lead">The following errors were detected:</p>';
        echo '<ul class="list-group list-group-flush">';
        foreach ($errorMessages as $errorMessage) {
            echo '<li class="list-group-item">' . $errorMessage . '</li>';
        }
        echo '</ul>';
        echo '<a class="btn btn-warning" href="login.php" role="button">Return to Login</a>';
    }
    ?>
</main>

<?php include "include/footer.inc.php" ?>
</body>
</html>