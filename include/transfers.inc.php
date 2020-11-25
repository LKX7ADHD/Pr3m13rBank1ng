<?php
require_once 'accounts.inc.php';

/**
 * Represents monetary value
 */
class Currency {
    /**
     * @var string amount of money
     */
    private $value;

    /**
     * @param $value string amount of money
     */
    public function __construct(string $value) {
        $this->value = trim($value);
    }

    /**
     * @return string
     */
    public function getValue(): string {
        return $this->value;
    }

    /**
     * @param $currency Currency amount of money to add to the receiver
     */
    public function add(Currency $currency) {
        $this->value = bcadd($this->value, $currency->value, 2);
    }

    /**
     * @param Currency $currency amount of money to subtract from the receiver
     */
    public function subtract(Currency $currency) {
        $this->value = bcsub($this->value, $currency->value, 2);
    }

    public function equalTo(Currency $other) {
        return bccomp($this->value, $other->value, 2) == 0;
    }

    public function lessThan(Currency $other) {
        return bccomp($this->value, $other->value, 2) == -1;
    }

    public function greaterThan(Currency $other) {
        return $other->lessThan($this);
    }

    public function lessThanOrEqualTo(Currency $other) {
        return !$other->lessThan($this);
    }

    public function greaterThanOrEqualTo(Currency $other) {
        return !$this->lessThan($other);
    }
}

/**
 * Sends money from an account to another
 * @param Account $sender Sender Account
 * @param Account $receiver Receiver Account
 * @param Currency $amount Currency Account
 * @return bool true if transfer was successful, false otherwise
 */
function performTransfer(Account $sender, Account $receiver, Currency $amount) {
    $transferred = true;

    // Initialise the currency classes for both the sender and receiver.
    $senderCurr = new Currency($sender->balance);
    $receiverCurr = new Currency($receiver->balance);

    $receiverCurr->add($amount);
    $senderCurr->subtract($amount);

    $receiverBalance = $receiverCurr->getValue();
    $senderBalance = $senderCurr->getValue();

    // Connecting to the database to update the data.
    if ($senderBalance > 0) {
        $conn = connectToDatabase();
        if ($conn->connect_error) {
            $transferred = false;
            http_response_code(500);
            die('Unable to connect to the database');

        } else {
            // Update the receiver account balance
            $stmt = $conn->prepare('UPDATE Accounts SET accountValue = ? WHERE accountNumber = ?');
            $stmt->bind_param('ss', $receiverBalance, $receiver->accountNumber);

            if (!$stmt->execute()) {
                $transferred = false;
                http_response_code(500);
                die('Unable to update receiver account balance');
            }

            // Update the sender account balance
            $stmt = $conn->prepare('UPDATE Accounts SET accountValue = ? WHERE accountNumber = ?');
            $stmt->bind_param('ss', $senderBalance, $sender->accountNumber);

            if (!$stmt->execute()) {
                $transferred = false;
                http_response_code(500);
                die('An unexpected error has occurred. Please try again later.');
            }

            // Get current date and time.
            $timestamp = date('Y-m-d H:i:s');
            $amountValue = $amount->getValue();

            $stmt = $conn->prepare('INSERT INTO Transfers (SenderId, ReceiverId, transfervalue, transfertimestamp) SELECT S.AccountID, R.AccountID, ?, ? FROM Accounts S, Accounts R WHERE S.accountNumber = ? AND R.accountNumber = ?');
            $stmt->bind_param('ssss', $amountValue, $timestamp, $sender->accountNumber, $receiver->accountNumber);

            if (!$stmt->execute()) {
                $transferred = false;
                http_response_code(500);
                die('An unexpected error has occurred. Please try again later.');
            }
            $stmt->close();
        }
        $conn->close();
    } else {
        $transferred = false;
        die('Not enough balance!');
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

?>