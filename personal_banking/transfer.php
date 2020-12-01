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
    if (!isset($_POST['senderAccountNumber']) || empty($_POST['senderAccountNumber'])) {
        $readyToTransfer = false;
        $errors[] = 'No sender account number specified';
    } else {
        $senderAccountNumber = str_replace('-', '', sanitiseInput($_POST['senderAccountNumber']));

        if (!isAccountNumberValid($senderAccountNumber)) {
            $readyToTransfer = false;
            $errors[] = 'Invalid sender account number';
        } else {
            foreach ($accounts as $account) {
                if ($senderAccountNumber === $account->accountNumber) {
                    $senderAccount = $account;
                }
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
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<title>Premier Banking | Transfer</title>
    <?php include '../include/imports.inc.php' ?>
</head>

<body>
<?php include '../include/navbar.inc.php' ?>
<header class="jumbotron text-center">
	<h1 class="display-4">Transfer</h1>
</header>

<main class="container">
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
                    echo '<a class="dropdown-item" href="#" data-accountNumber="' . $account->accountNumber . '">' . $account->accountName . '</a>';
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
                    echo '<a class="dropdown-item" href="#" data-accountNumber="' . $account->accountNumber . '">' . $account->accountName . '</a>';
                }
                ?>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item" href="#" data-otherAccount>Other account</a>
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
				       name="amount" min="0.01" step="0.01" required>
			</div>
		</div>

		<button type="submit" class="btn btn-primary btn-lg mb-3">Transfer</button>
	</form>

	<script>
        $('.account-dropdown a.dropdown-item').on('click', e => {
            const dropdown = $(e.target).parents('.dropdown')
            dropdown.children('button').text($(e.target).text())

            if (typeof ($(e.target).attr('data-otherAccount')) !== 'undefined') {
                // Other account
                dropdown.next().find('input').val('')
                dropdown.next().removeClass('d-none')

                $('a.dropdown-item').removeClass('disabled')
            } else {
                // One of own accounts
                dropdown.next().addClass('d-none')
                dropdown.next().find('input').val($(e.target).attr('data-accountNumber'))

                $('a.dropdown-item').removeClass('disabled')
                $('.account-dropdown').not(dropdown).find('a.dropdown-item[data-accountNumber="' + $(e.target).attr('data-accountNumber') + '"]').addClass('disabled')
            }
        })

        $('input[name=senderAccountNumber], input[name=receiverAccountNumber]').on('input', e => {
            const input = $(e.target)
            const dashIndices = [3, 9]
            let caretPos = e.target.selectionStart

            for (let i = 0; i < input.val().length; i++) {
                if (dashIndices.includes(i)) {
                    if (input.val().length > i && input.val()[i] !== '-') {
                        input.val(input.val().slice(0, i) + '-' + input.val().slice(i))
                        if (caretPos === i + 1) {
                            caretPos++
                        }
                    } else if (input.val().length === i + 1 && input.val()[i] === '-') {
                        input.val(input.val().slice(0, i))
                    }
                } else if (input.val()[i] === '-') {
                    input.val(input.val().slice(0, i) + input.val().slice(i + 1))
                }
            }

            e.target.setSelectionRange(caretPos, caretPos)
        }).on('change', e => {
            const value = $(e.target).val().replaceAll('-', '')

            if (value.length !== 10) {
                e.target.setCustomValidity("Invalid account number")
                return
            }

            let accumulator = 0;
            for (let i = 0; i < 8; i++) {
                accumulator += (parseInt(value[i]) * (17 ** i)) % 17
            }

            if (value.slice(-2) !== (accumulator % 17).toString().padStart(2, '0')) {
                e.target.setCustomValidity("Invalid account number")
            } else {
                e.target.setCustomValidity("")
            }
        })

        $('#transfer-form').on('submit', e => {
            let valid = true
            $('#sending-account-invalid-warning, #recipent-account-invalid-warning').addClass('d-none')

            if ($('input[name=senderAccountNumber]').val() === '') {
                $('#sending-account-invalid-warning').removeClass('d-none')
                valid = false
            }

            if ($('input[name=receiverAccountNumber]').val() === '') {
                $('#recipent-account-invalid-warning').removeClass('d-none')
                valid = false
            }

            return valid
        })
	</script>

</main>
<?php include "../include/sessionTimeout.inc.php" ?>
<?php include '../include/footer.inc.php' ?>
</body>
</html>
