<?php
require_once '../include/accounts.inc.php';
require_once '../include/sessiontimeout.inc.php';

$user = getAuthenticatedUser();
$accounts = getAccounts($user);

$transactionsData = getTransactions($accounts);

$transaction = array();
$accID = array();
list($accID, $transaction) = $transactionsData;



if (!$user) {
    header('Location: login.php');
    exit();
}

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

    <section class="transfers">
        <h3>
            Transaction History
        </h3>

        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">Date</th>
                <th scope="col">Account</th>
                <th scope="col">Transaction</th>
                <th scope="col">Deposit</th>
                <th scope="col">Withdrawal</th>
            </tr>
            </thead>
            <tbody>

<?php
                foreach ($transaction as $txn)
                {

                    echo '<tr>';
                    echo '<th scope="row">' . date('d/m/Y',strtotime($txn['transferTimestamp'])) . '</th>';


                    foreach ($accounts as $account) {
                        echo '<th>' . $account->getAccountNumberRepresentation() . '</th>';
                    }
                    echo '<th></th>';
                    foreach($accID as $acc) {
                        if ((int)implode($acc) == $txn['ReceiverID'])
                        {
                            echo "<th>" . $txn['transferValue'] . "</th>";
                            echo "<th>" . "-" . "</th>";
                        } else {
                            echo "<th>" . "-" . "</th>";
                            echo "<th>" . $txn['transferValue'] . "</th>";
                        }
                    }
                    echo '</tr>';
                }
            ?>


            </tbody>
        </table>
    </section>

</main>
<?php include "../include/footer.inc.php" ?>
</body>

</html>
