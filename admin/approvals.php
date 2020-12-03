<?php
require_once '../include/accounts.inc.php';

$user = getAuthenticatedUser();

if (!$user || !$user->admin) {
    header('Location: ../login.php');
    exit();
}

$requests = getAccountApplications(NULL, 0);
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Premier Banking | Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php include "../include/imports.inc.php" ?>
</head>

<body class="dashboard-body">
<?php include '../include/navbar.inc.php' ?>


<main class="container">
    <section class="transfers">
        <h3>Pending approvals</h3>

        <div class="table-responsive">
            <table class="table table-bordered" id="approvals-table">
                <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">User</th>
                    <th scope="col">Account name</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody>

                <?php
                foreach ($requests as $request) {
                    echo '<tr>';
                    echo '<td>' . date('d/m/Y', strtotime($request['requestTimestamp'])) . '</td>';
                    echo '<td>' . $request['username'] . '</td>';
                    echo '<td>' . $request['accountName'] . '</td>';

                    echo '<td><div class="btn-group " role="group" aria-label="Actions">';

                    echo '<button type="button" class="btn btn__primary request-approval-btn" data-approve="true" data-requestNumber="' . $request['accountNumber'] . '">Approve</button>';
                    echo '<button type="button" class="btn btn__primary request-approval-btn" data-approve="false" data-requestNumber="' . $request['accountNumber'] . '">Reject</button>';

                    echo '</div></td>';
                    echo '</tr>';
                }
                ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<?php include "../include/sessionTimeout.inc.php" ?>
<?php include "../include/footer.inc.php" ?>
</body>

</html>
