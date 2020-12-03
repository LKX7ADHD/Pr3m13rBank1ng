<?php
require_once '../include/accounts.inc.php';

$user = getAuthenticatedUser();

if (!$user) {
    header('Location: ../login.php');
    exit();
}

$success = true;
$errorMessages = array();

if (isset($_POST['name']) && !empty($_POST['name'])) {
    $name = sanitiseInput($_POST['name']);
} else {
    $errorMessages[] = 'Account name is required';
    $success = false;
}

if ($success) {
    createAccountApplication($user, $name);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Apply for a new account under Premier Banking!">
	<title>Premier Banking</title>
    <?php include '../include/imports.inc.php' ?>
</head>
<body>
<?php include "../include/navbar.inc.php" ?>

<header class="jumbotron text-center">
	<h1 class="display-4">Open new Account</h1>
</header>
<main class="container px-4">
	<section class="row">
		<div class="col">
            <?php
            if ($success) {
                echo '<p class="h1">Successfully applied</p>';
                echo '<p class="lead">Your application has been received by our admins and will be processed within 3 business days.</p>';
                echo '<a class="btn btn-success btn-lg mt-4 mb-3" href="/personal_banking/" role="button">Return to Dashboard</a>';
            } else {
                echo '<p class="h1">Oops!</p>';
                echo '<p class="lead">The following errors were detected:</p>';
                echo '<ul class="list-group list-group-flush">';
                foreach ($errorMessages as $errorMessage) {
                    echo '<li class="list-group-item">' . $errorMessage . '</li>';
                }
                echo '</ul>';
                echo '<a class="btn btn-danger btn-lg mt-4 mb-3" href="newAccountApplication.php" role="button">Return to Application</a>';
            }
            ?>
		</div>
	</section>
</main>

<?php include "../include/sessionTimeout.inc.php" ?>
<?php include "../include/footer.inc.php" ?>
</body>
</html>
