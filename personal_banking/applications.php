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
    <title>Premier Banking | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include "../include/imports.inc.php" ?>
</head>

<body class="dashboard-body">
<?php include '../include/navbar.inc.php' ?>

<main class="container">
    <section class="transfers">
        <h3>Applications</h3>

        <a class="btn btn-primary btn-lg my-4" href="newAccountApplication.php" role="button">Open new Account</a>

        <ul class="list-group">
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