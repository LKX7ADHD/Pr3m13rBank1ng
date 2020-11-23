<?php

include 'accounts.inc.php';
$user = getAuthenticatedUser();
$senderAccounts = getAccounts($user);
$errorMessages = array();

if (!$user) {
    header('Location: login.php');
    exit();
}

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



// Get the receiver account ID from user input
if (isset($_POST['receiverAccountNumber']) && !empty($_POST['receiverAccountNumber'])) {
    $accNum = sanitiseInput($_POST['accountNumber']);

}

// Get the amount that the user wants to transfer
if (isset($_POST['transferredAmount']) && !empty($_POST['transferredAmount'])) {
    $value_amount = sanitiseInput($_POST['accountNumber']);
    $amount = new Currency($value_amount);
}

/**
 * Get sender Accounts information.
 * @param $senderAcc array Accounts for the given users.
 * @return Account|false the sender account details, or false if user did not select any accounts.
 */

function getSenderAccounts(array $senderAcc) {
    foreach ($senderAcc as $senderAccount) {
        if (isset($_POST['senderAcc']) && !empty($_POST['senderAcc'])) {
            if ($_POST['senderAcc'] === $senderAccount) {
                return $senderAccount;
            }
        }
    }
    return false;
}

/**
 * Get Receiver Accounts information.
 * @param $receiverAcc string Accounts given from user input.
 * @return Account|false the sender account details, or false if user did not select any accounts.
 */

function getReceiverAccounts(string $receiverAcc) {

    return getAccount($receiverAcc);
}

/**
 * Sends money from an account to another
 * @param Account $sender Sender Account
 * @param Account $receiver Receiver Account
 * @param Currency $amount Currency Account
 * @return bool weather the transferred is successful or not.
 */

function performTransfer(Account $sender, Account $receiver, Currency $amount) {
    $transferred = true;

    // Initialise the currency classes for both the sender and receiver.
    $senderCurr = new Currency($sender->balance);
    $receiverCurr = new Currency($receiver->balance);
    $newReceiverBal = $receiverCurr->add($amount->getValue());
    $newSenderBal = $senderCurr->subtract($amount->getValue());


    // Connecting to the database to update the data.
    if ($newSenderBal > 0) {
        $conn = connectToDatabase();
        if ($conn->connect_error) {
            $transferred = false;
            http_response_code(500);
            die('Unable to connect to the database');

        } else {
            // Update the receiver account balance
            $stmt = $conn->prepare('UPDATE accounts SET accountValue = ? WHERE accountNumber =?;');
            $stmt->bind_param('ss', $newReceiverBal, $receiver->accountNumber);

            if (!$stmt->execute()) {
                $transferred = false;
                http_response_code(500);
                die('Unable to update receiver account balance');
            }
            // Update the sender account balance
            $stmt = $conn->prepare('UPDATE accounts SET accountValue = ? WHERE accountNumber =?;');
            $stmt->bind_param('ss', $newSenderBal, $sender->accountNumber);

            if (!$stmt->execute()) {
                $transferred = false;
                http_response_code(500);
                die('An unexpected error has occurred. Please try again later.');
            }

            // Get current date and time.
            $current = date('Y-m-d H:i:s');

            $stmt = $conn->prepare('INSERT INTO transfers (senderid, receiverid, transfervalue, transfertimestamp) VALUES (?,?,?,?)');
            $stmt->bind_param('iiss', $sender->user, $receiver->user, $amount->getValue(), $current);

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
 * @return bool weather the reversed transferred is successful or not.
 */
function reverseTransfer(int $transferID)
{
    $reversed = true;

    $conn = connectToDatabase();
    if ($conn->connect_error) {
        $transferred = false;
        http_response_code(500);
        die('Unable to connect to the database');
    } else {
    // Update the reverseTransfer status
    $stmt = $conn->prepare('UPDATE transfers SET reverseTransfer = 1 WHERE TransferID =?;');
    $stmt->bind_param('i', $transferID);

    if (!$stmt->execute()) {
        $transferred = false;
        http_response_code(500);
        die('Unable to update receiver account balance');
    }
    $stmt->close();
}
$conn->close();
    return $reversed;

}
?>