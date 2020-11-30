<?php
require_once '../include/accounts.inc.php';
require_once '../include/transfers.inc.php';
require_once '../include/sessiontimeout.inc.php';

$user = getAuthenticatedUser();

if (!$user) {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Premier Banking|Transfers</title>
    <?php include '../include/imports.inc.php' ?>
</head>
<body>
<?php include "../include/navbar.inc.php" ?>

<header class="jumbotron text-center">
	<h1 class="display-4">Transfer Result</h1>
</header>
<main class="container process-login">
    <?php
    echo '<p class="h1">Transferred Successful</p>';

    echo '</ul>';
    echo '<a class="btn btn-success btn-lg" href="/personal_banking/" role="button">Return to Dashboard</a>';
    ?>
</main>

<?php include "../include/footer.inc.php" ?>
</body>
</html>
