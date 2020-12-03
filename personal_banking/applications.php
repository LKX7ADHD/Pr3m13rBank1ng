<?php
require_once '../include/accounts.inc.php';

$user = getAuthenticatedUser();
if (!$user) {
    header('Location: ../login.php');
    exit();
}

$applications = getAccountApplications($user, NULL);
$applicationStatus = array('Pending', 'Approved', 'Rejected');

?>
<!DOCTYPE html>

<html lang="en">

<head>
	<meta charset="UTF-8">
	<title>Premier Banking | Applications</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Review your account applications under Premier Banking!">
	<?php include "../include/imports.inc.php" ?>
</head>

<body class="dashboard-body">
<?php include '../include/navbar.inc.php' ?>

<main class="container">
	<section>
		<h1>Applications</h1>
		<p class="lead">Review applications you have made</p>

		<ul class="list-group mt-4">
            <?php
            if (count($applications) == 0) {
                echo '<p class="lead">No Applications</p>';
            } else {
                foreach ($applications as $application) {
                    echo '<li class="list-group-item"><p class="h3">' . $application['accountName'] . '</p>' . $applicationStatus[$application['status']] . '<p class="text-muted mt-1 mb-0">' . date('d/m/Y', strtotime($application['requestTimestamp'])) . '</p></li>';
                }
            }
            ?>
		</ul>
	</section>
</main>
<?php include "../include/sessionTimeout.inc.php" ?>
<?php include "../include/footer.inc.php" ?>
</body>
</html>
