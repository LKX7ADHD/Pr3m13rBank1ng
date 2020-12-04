<?php
require_once 'accounts.inc.php';

/**
 * Sends money from an account to another
 * @param Account $sender sender account
 * @param Account $receiver receiver account
 * @param Currency $amount currency account
 * @param array $errors stores error messages if available
 * @return bool true if transfer was successful, false otherwise
 */
function performTransfer(Account $sender, Account $receiver, Currency $amount, array &$errors = array()) {
    $transferred = true;

    $senderCurr = $sender->balance;
    $receiverCurr = $receiver->balance;

    $receiverCurr->add($amount);
    $senderCurr->subtract($amount);

    $receiverBalance = $receiverCurr->getValue();
    $senderBalance = $senderCurr->getValue();

    if ($senderBalance < 0) {
        $transferred = false;
        $errors[] = 'Not enough balance!';
    } elseif ($receiverBalance > 9999999999999.99) {
        $transferred = false;
        $errors[] = 'The recipient account is not available to transfers at the moment. Please try again later.';
    } else {
        $conn = connectToDatabase();
        if ($conn->connect_error) {
            $transferred = false;
            http_response_code(500);
            $errors[] = 'An unexpected error has occurred. Please try again later.';

        } else {
            // Update the receiver account balance
            $stmt = $conn->prepare('UPDATE Accounts SET accountValue = ? WHERE accountNumber = ?');
            $stmt->bind_param('ss', $receiverBalance, $receiver->accountNumber);

            if (!$stmt->execute()) {
                $transferred = false;
                http_response_code(500);
                $errors[] = 'An unexpected error has occurred. Please try again later.';
            }

            // Update the sender account balance
            $stmt = $conn->prepare('UPDATE Accounts SET accountValue = ? WHERE accountNumber = ?');
            $stmt->bind_param('ss', $senderBalance, $sender->accountNumber);

            if (!$stmt->execute()) {
                $transferred = false;
                http_response_code(500);
                $errors[] = 'An unexpected error has occurred. Please try again later.';
            }

            // Get current date and time.
            $timestamp = date('Y-m-d H:i:s');
            $amountValue = $amount->getValue();

            $stmt = $conn->prepare('INSERT INTO Transfers (SenderId, ReceiverId, transfervalue, transfertimestamp) SELECT S.AccountID, R.AccountID, ?, ? FROM Accounts S, Accounts R WHERE S.accountNumber = ? AND R.accountNumber = ?');
            $stmt->bind_param('ssss', $amountValue, $timestamp, $sender->accountNumber, $receiver->accountNumber);

            if (!$stmt->execute()) {
                $transferred = false;
                http_response_code(500);
                $errors[] = 'An unexpected error has occurred. Please try again later.';
            }
            $stmt->close();
        }
        $conn->close();
    }

    return $transferred;
}
