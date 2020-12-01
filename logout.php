<?php
require_once 'include/accounts.inc.php';

$logOutDueToSessionExpiry = true;
if (getAuthenticatedUser()) {
    $logOutDueToSessionExpiry = false;
}

logOut();
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
	<h1 class="display-4">Logout</h1>
</header>
<main class="container logout">
    <?php
    if ($logOutDueToSessionExpiry) {
        echo '<p class="h1">Session expired</p>';
        echo '<p class="lead">You were logged out to protect your account as you were inactive for some time.</p>';
        echo '<a class="btn btn-success btn-lg mt-4" href="/login.php" role="button">Log in again</a>';
    } else {
        echo '<p class="h1">Logout success</p>';
        echo '<a class="btn btn-success btn-lg mt-4" href="/" role="button">Return to Home</a>';
    }
    ?>
</main>

<?php include "include/sessionTimeout.inc.php" ?>
<?php include "include/footer.inc.php" ?>
</body>

</html>
