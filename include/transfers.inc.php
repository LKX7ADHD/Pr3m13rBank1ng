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

    // Initialise the currency classes for both the sender and receiver.
    $senderCurr = $sender->balance;
    $receiverCurr = $receiver->balance;

    $receiverCurr->add($amount);
    $senderCurr->subtract($amount);

    $receiverBalance = $receiverCurr->getValue();
    $senderBalance = $senderCurr->getValue();

    // Connecting to the database to update the data.
    if ($senderBalance >= 0) {
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
    } else {
        $transferred = false;
        $errors[] = 'Not enough balance!';
    }

    return $transferred;
}

/**
 * Reverse a transfer
 * @param int $transferID
 * @return bool true if reverse was successful, false otherwise
 */
function reverseTransfer(int $transferID) {
    // TODO : DOESNT ACTUALLY CREDIT THE TWO ACCOUNTS YET

    $reversed = true;

    $conn = connectToDatabase();
    if ($conn->connect_error) {
        $reversed = false;
        http_response_code(500);
        die('Unable to connect to the database');
    } else {
        // Update the reverseTransfer status
        $stmt = $conn->prepare('UPDATE Transfers SET reverseTransfer = 1 WHERE TransferID = ?');
        $stmt->bind_param('i', $transferID);

        if (!$stmt->execute()) {
            $reversed = false;
            http_response_code(500);
            die('Unable to update receiver account balance');
        }
        $stmt->close();
    }

    $conn->close();
    return $reversed;

}
