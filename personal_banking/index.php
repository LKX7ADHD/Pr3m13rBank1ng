<?php
require_once '../include/accounts.inc.php';
require_once '../include/sessiontimeout.inc.php';

$user = getAuthenticatedUser();
$accounts = getAccounts($user);

$transfers = getTransfers($accounts);

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
		<h3>Transaction History</h3>

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
            foreach ($transfers as $transfer) {
                $value = new Currency($transfer['transferValue']);

                echo '<tr>';
                echo '<td scope="row">' . date('d/m/Y', strtotime($transfer['transferTimestamp'])) . '</td>';

                foreach ($accounts as $account) {
                    echo '<td>' . $account->getAccountNumberRepresentation() . '</td>';
                }

                echo '<td></td>';
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
	</section>

</main>
<?php include "../include/footer.inc.php" ?>
</body>

</html>
