<?php
require_once '../include/accounts.inc.php';

$user = getAuthenticatedUser();

if (!$user || !$user->admin) {
    header('Location: ../login.php');
    exit();
}

$transfers = getTransfers();
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
        <h3>Review transfers</h3>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Sending account</th>
                <th scope="col">Receiving account</th>
                <th scope="col">Transaction</th>
                <th scope="col">Amount</th>
            </tr>
            </thead>
            <tbody>

            <?php
            foreach ($transfers as $transfer) {
                $value = new Currency($transfer['transferValue']);

                echo '<tr>';
                echo '<td>' . date('d/m/Y', strtotime($transfer['transferTimestamp'])) . '</td>';

                echo '<td>' . Account::getAccountNumberRepresentationFromString($transfer['Sender']) . '</td>';
                echo '<td>' . Account::getAccountNumberRepresentationFromString($transfer['Receiver']) . '</td>';

                echo '<td></td>';
                echo '<td>' . $value->getRepresentation() . '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </section>

</main>
<?php include "../include/sessionTimeout.inc.php" ?>
<?php include "../include/footer.inc.php" ?>
</body>

</html>
