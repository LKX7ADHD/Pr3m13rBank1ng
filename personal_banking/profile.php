<?php
include_once '../include/accounts.inc.php';
$user = getAuthenticatedUser();

if (!$user) {
    header('Location: login.php');
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title></title>
    <?php include '../include/imports.inc.php' ?>
</head>
<body>
<?php include '../include/navbar.inc.php' ?>
<header class="jumbotron text-center">
	<h1 class="display-4">Profile</h1>
</header>
<main class="container profile">
	<p class="h1">Hello, <?php echo $user->username; ?></p>
	<a class="btn btn-warning btn-lg mt-4" href="../logout.php" role="button">Logout</a>
</main>
<?php include "../include/sessionTimeout.inc.php" ?>
<?php include '../include/footer.inc.php' ?>
</body>
</html>
