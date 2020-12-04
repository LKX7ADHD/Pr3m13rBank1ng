<?php
require_once '../include/accounts.inc.php';

$user = getAuthenticatedUser();
if (!$user) {
    header('Location: ../login.php');
    exit();
}

$accounts = getAccounts($user);
$transfers = getTransfers($user);

?>
<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Premier Banking | Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description"
          content="A page where all your transactions and accounts are listed. This page allows you to open accounts too.">
    <?php include "../include/imports.inc.php" ?>
</head>

<body class="dashboard-body">
<?php include '../include/navbar.inc.php' ?>

<main class="container">
    <section class="transfers">
        <h1>Accounts</h1>

        <a class="btn btn__primary btn-lg my-4" href="newAccountApplication.php" role="button">Open new Account</a>

        <ul class="list-group">
            <?php
            if (count($accounts) == 0) {
                echo '<p class="lead">No Accounts</p>';
            } else {
                foreach ($accounts as $account) {
                    echo '<li class="list-group-item"><p class="h3">' . $account->getBalanceRepresentation() . '</p>' . $account->accountName . '<p class="text-muted mt-1 mb-0">' . $account->getAccountNumberRepresentation() . '</p></li>';
                }
            }
            ?>
        </ul>
    </section>

    <section class="transfers">
        <h1>Transaction History</h1>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th scope="col">Date</th>
                    <th scope="col">Sending Account</th>
                    <th scope="col">Receiving Accunt</th>
                    <th scope="col">Deposit</th>
                    <th scope="col">Withdrawal</th>
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

                    if ($transfer['deposit']) {
                        echo "<td>" . $value->getRepresentation() . "</td>";
                        echo "<td>-</td>";
                    } else {
                        echo "<td>-</td>";
                        echo "<td>" . $value->getRepresentation() . "</td>";
                    }
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
