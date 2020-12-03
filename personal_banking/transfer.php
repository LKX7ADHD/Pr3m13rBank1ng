<?php
require_once '../include/accounts.inc.php';
require_once '../include/transfers.inc.php';

$user = getAuthenticatedUser();

if (!$user) {
    header('Location: ../login.php');
    exit();
}

$accounts = getAccounts($user);
$readyToTransfer = true;
$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['verified'])) {
        $errors[] = 'The transfer was not performed as you did not verify your intention';
        $readyToTransfer = false;
    }

    if (!isset($_POST['senderAccountNumber']) || empty($_POST['senderAccountNumber'])) {
        $readyToTransfer = false;
        $errors[] = 'No sender account number specified';
    } else {
        $senderAccountNumber = str_replace('-', '', sanitiseInput($_POST['senderAccountNumber']));

        if (!isAccountNumberValid($senderAccountNumber)) {
            $readyToTransfer = false;
            $errors[] = 'Invalid sender account number';
        } else {
            foreach ($accounts as $account) if ($senderAccountNumber === $account->accountNumber) {
                $senderAccount = $account;
            }
            if (!$senderAccount) {
                $readyToTransfer = false;
                $errors[] = 'Invalid sender account number';
            }
        }
    }

    if (!isset($_POST['receiverAccountNumber']) || empty($_POST['receiverAccountNumber'])) {
        $readyToTransfer = false;
        $errors[] = 'No recipient account number specified';
    } else {
        $receiverAccountNumber = str_replace('-', '', sanitiseInput($_POST['receiverAccountNumber']));

        if (!isAccountNumberValid($receiverAccountNumber)) {
            $readyToTransfer = false;
            $errors[] = 'Invalid recipient account number';
        } else {
            $receiverAccount = getAccount($receiverAccountNumber);

            if (!$receiverAccount) {
                $readyToTransfer = false;
                $errors[] = 'Invalid recipient account number';
            }
        }
    }

    if (isset($senderAccountNumber) && isset($receiverAccountNumber) && $senderAccountNumber === $receiverAccountNumber) {
        $readyToTransfer = false;
        $errors[] = 'Cannot transfer to the same account';
    }

    if (!isset($_POST['amount']) || empty($_POST['amount']) || !filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT)) {
        $readyToTransfer = false;
        $errors[] = 'No amount specified';
    } else {
        $amountValue = sanitiseInput($_POST['amount']);
        $amount = new Currency($amountValue);

        if ($amount->lessThan(new Currency('0'))) {
            $readyToTransfer = false;
            $errors[] = 'Invalid amount';
        }
    }

    if ($readyToTransfer) {
        if (performTransfer($senderAccount, $receiverAccount, $amount, $errors)) {
            header('Location: transferSuccess.php');
            exit();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Premier Banking | Transfer</title>
    <?php include '../include/imports.inc.php' ?>
</head>

<body class="dashboard-body">
<?php include '../include/navbar.inc.php' ?>

<main class="container">
    <section>
        <h1 class="mb-4">Transfer</h1>

        <?php

        if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
            }
        }

        ?>
        <form method="POST" id="transfer-form">
            <p class="lead">Sending account</p>

            <div class="dropdown account-dropdown mb-3" id="senderDropdown">
                <button type="button" class="btn btn-light dropdown-toggle" id="senderDropdownButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Select sending account
                </button>
                <div class="dropdown-menu" aria-labelledby="senderDropdownButton">
                    <span class="dropdown-header">My accounts</span>
                    <?php
                    foreach ($accounts as $account) {
                        echo '<a class="dropdown-item" data-accountNumber="' . $account->accountNumber . '">' . $account->accountName . '</a>';
                    }
                    ?>
                </div>
            </div>

            <div class="form-group mb-3 d-none">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="account-number-from">Send from</span>
                    </div>
                    <input type="text" class="form-control" aria-label="Send from" aria-describedby="account-number-from"
                           placeholder="Account number" name="senderAccountNumber" minlength="12" maxlength="12">
                </div>
            </div>

            <p id="sending-account-invalid-warning" class="text-danger d-none mb-4">Please specify an account to transfer
                from</p>
            <p class="lead">Recipient account</p>

            <div class="dropdown account-dropdown mb-3" id="receiverDropdown">
                <button type="button" class="btn btn-light dropdown-toggle" id="receiverDropdownButton"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Select recipient account
                </button>
                <div class="dropdown-menu" aria-labelledby="receiverDropdownButton">
                    <span class="dropdown-header">My accounts</span>
                    <?php
                    foreach ($accounts as $account) {
                        echo '<a class="dropdown-item" data-accountNumber="' . $account->accountNumber . '">' . $account->accountName . '</a>';
                    }
                    ?>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" data-otherAccount>Other account</a>
                </div>
            </div>

            <div class="form-group mb-3 d-none">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text" id="account-number-to">Send to</span>
                    </div>
                    <input type="text" class="form-control" aria-label="Send to" aria-describedby="account-number-to"
                           placeholder="Account number" name="receiverAccountNumber" minlength="12" maxlength="12">
                </div>
            </div>

            <p id="recipent-account-invalid-warning" class="text-danger d-none mb-4">Please specify an account to transfer
                to</p>
            <label for="amount" class="d-block lead">Amount to send</label>

            <div class="form-group mb-3">
                <div class="input-group mb-3">
                    <div class="input-group-prepend">
                        <span class="input-group-text">$</span>
                    </div>
                    <input type="number" class="form-control" aria-label="Amount" placeholder="Amount" id="amount"
                           name="amount" min="0.01" step="0.01" autocomplete="transaction-amount" required>
                </div>
            </div>

            <div class="form-check" id="form-agree">
                <input class="form-check-input" type="checkbox" name="verified" aria-label="Checkbox" id="verified" required/>
                <label class="form-check-label" for="verified">I have checked that the accounts and amount entered is correct</label>
            </div>

            <button type="submit" class="btn btn__primary btn-lg mt-4 mb-3">Transfer</button>
        </form>
    </section>
</main>
<?php include "../include/sessionTimeout.inc.php" ?>
<?php include '../include/footer.inc.php' ?>
</body>
</html>
