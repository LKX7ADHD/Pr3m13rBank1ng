<?php

$lifetime = 1200; // 20 minutes
$expiry = time() + $lifetime;

session_set_cookie_params($lifetime, '/', $_SERVER['HTTP_HOST'], true, true);
session_name('session');
session_start();

// Renew cookie lifetime
setcookie(session_name(), session_id(), $expiry, '/', $_SERVER['HTTP_HOST'], true, true);
$_SESSION['session_expiry'] = $expiry;

// TODO : REMOVE WHEN DONE

error_reporting(E_ALL);
ini_set('display_errors', '1');

/**
 * Encapsulates user information
 */
class User {
    public $username;
    public $firstName;
    public $lastName;
    public $email;
    public $admin;
    public $verified;
}

/**
 * Encapsulates account information
 */
class Account {
    /**
     * @var string name of the account
     */
    public $accountName;

    /**
     * @var string unique account number representing the account
     */
    public $accountNumber;

    /**
     * @var User the owner of the account
     */
    public $user;

    /**
     * @var Currency amount of money stored in account
     */
    public $balance;

    /**
     * Get a representation of the balance for display
     * @return string
     */
    public function getBalanceRepresentation() {
        return $this->balance->getRepresentation();
    }

    /**
     * Get a representation of account number for display
     * @return string
     */
    public function getAccountNumberRepresentation() {
        return Account::getAccountNumberRepresentationFromString($this->accountNumber);
    }

    public static function getAccountNumberRepresentationFromString($accountNumberString) {
        return substr($accountNumberString, 0, 3) . '-' . substr($accountNumberString, 3, 5) . '-' . substr($accountNumberString, -2);
    }
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

    public function getValue() {
        return $this->value;
    }

    /**
     * Get a representation of value for display
     * @return string
     */
    public function getRepresentation() {
        $n = (strlen($this->value) - 1) % 3 + 1;
        $representation = '$' . substr($this->value, 0, $n);

        for ($i = 0; $i < ceil(strlen($this->value) / 3) - 2; $i++) {
            $representation .= ',' . substr($this->value, $n + 3 * $i, 3);
        }

        $representation .= substr($this->value, -3);
        return $representation;
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
 * Attempts to connect to the database
 * @return mysqli connection object
 */
function connectToDatabase() {
//    AWS method
    $config = parse_ini_file('/var/private/db-config.ini');
    return new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

//    Heroku method
//    $servername = getenv('heroku_db_servername');
//    $username = getenv('heroku_db_username');
//    $password = getenv('heroku_db_password');
//    $dbname = getenv('heroku_db_dbname');
//    return new mysqli($servername, $username, $password, $dbname);
}

/**
 * Attempts to register a user
 *
 * WARNING:
 * This function makes no effort to check if the input information is valid. If invalid
 * information is passed in (for example, duplicate emails), the function will exit and
 * raise an Internal Server Error.
 *
 * Make sure all information passed into this function is properly validated beforehand.
 *
 * @param $user User the user to register
 * @param $hashed_password string the password for the user to login with, hashed
 */
function registerUser(User $user, string $hashed_password) {
    $conn = connectToDatabase();
    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('INSERT INTO Users (username, firstName, lastName, email, password) VALUES (?,?,?,?,?)');
        $stmt->bind_param('sssss', $user->username, $user->firstName, $user->lastName, $user->email, $hashed_password);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $stmt->close();
    }
    $conn->close();
}

/**
 * Attempts to authenticate a user with an email-password pair
 * @param $email string email of user
 * @param $password string password of user
 * @return bool true if user is successfully authenticated, false otherwise
 */
function authenticateUser(string $email, string $password) {
    $authenticated = false;
    $conn = connectToDatabase();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('SELECT username, firstName, lastName, password, isAdmin, isVerified FROM Users WHERE email = ?');
        $stmt->bind_param('s', $email);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }

        $result = $stmt->get_result();
        $stmt->close();
    }
    $conn->close();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    }

    if (isset($row) && password_verify($password, $row['password'])) {
        $authenticated = true;

        $user = new User();
        $user->username = $row['username'];
        $user->firstName = $row['firstName'];
        $user->lastName = $row['lastName'];
        $user->email = $email;
        $user->admin = $row['isAdmin'];
        $user->verified = $row['isVerified'];

        $_SESSION['user'] = $user;
    }

    $row = NULL;
    unset($row);

    return $authenticated;
}

/**
 * Checks if the email provided is registered with an existing user
 * @param $email string email to check
 * @return bool true if email is registered, false otherwise
 */
function isEmailRegistered(string $email) {
    $isRegistered = false;
    $conn = connectToDatabase();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('SELECT firstName FROM Users WHERE email = ?');
        $stmt->bind_param('s', $email);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $result = $stmt->get_result();
        $stmt->close();
    }
    $conn->close();

    if ($result->num_rows > 0) {
        $isRegistered = true;
    }
    return $isRegistered;
}

/**
 * Checks if the username provided is registered with an existing user
 * @param $username string username to check
 * @return bool true if username is registered, false otherwise
 */
function isUsernameRegistered(string $username) {
    $isRegistered = false;
    $conn = connectToDatabase();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('SELECT firstName FROM Users WHERE username = ?');
        $stmt->bind_param('s', $username);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $result = $stmt->get_result();
        $stmt->close();
    }
    $conn->close();
    if ($result->num_rows > 0) {
        $isRegistered = true;
    }
    return $isRegistered;
}

/**
 * Retrieves information about the current logged in user
 * @return User|false user object if logged in, false otherwise
 */
function getAuthenticatedUser() {
    if (isset($_SESSION['user'])) {
        return $_SESSION['user'];
    } else {
        return false;
    }
}

/**
 * Retrieves accounts owned by the specified user
 * @param User $user the user to retrieve accounts for
 * @return Account[] accounts
 */
function getAccounts(User $user) {
    $accounts = array();
    $conn = connectToDatabase();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('SELECT Accounts.accountName, Accounts.accountValue, Accounts.accountNumber FROM Accounts INNER JOIN Users ON Accounts.UserID=Users.UserID WHERE Users.username = ?');
        $stmt->bind_param('s', $user->username);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }

        $result = $stmt->get_result();
        $stmt->close();
    }
    $conn->close();
    while ($row = $result->fetch_assoc()) {
        $account = new Account();
        $account->user = $user;
        $account->accountName = $row['accountName'];
        $account->balance = new Currency($row['accountValue']);
        $account->accountNumber = $row['accountNumber'];
        $accounts[] = $account;
    }
    return $accounts;
}

/**
 * Retrieves account details
 * @param string $accountNumber
 * @return Account|false the account details, or false if account number does not belong to any account
 */
function getAccount(string $accountNumber) {

    if (!isAccountNumberValid($accountNumber)) {
        return false;
    }
    $account = false;
    $conn = connectToDatabase();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('SELECT A.accountName, A.accountValue, U.username, U.firstName, U.lastName, U.email, U.isAdmin, U.isVerified FROM Accounts A INNER JOIN Users U ON A.UserID = U.UserID WHERE A.accountNumber = ?');
        $stmt->bind_param('s', $accountNumber);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }

        $result = $stmt->get_result();
        $stmt->close();
    }
    $conn->close();
    while ($row = $result->fetch_assoc()) {
        $user = new User();
        $user->username = $row['username'];
        $user->firstName = $row['firstName'];
        $user->lastName = $row['lastName'];
        $user->email = $row['email'];
        $user->admin = $row['isAdmin'];
        $user->verified = $row['isVerified'];

        $account = new Account();
        $account->user = $user;
        $account->accountName = $row['accountName'];
        $account->balance = new Currency($row['accountValue']);
        $account->accountNumber = $accountNumber;
    }
    return $account;
}

/**
 * Creates a new account
 * @param User $user the owner of the new account
 * @param string $accountName name of the account to be created
 */
function createAccount(User $user, string $accountName) {
    $conn = connectToDatabase();

    $findAccountNumber = true;
    while ($findAccountNumber) {
        $accountNumber = generateAccountNumber();
        if (!getAccount($accountNumber)) {
            $findAccountNumber = false;
        }
    }

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('INSERT INTO Accounts (UserID, accountName, accountValue, accountNumber) SELECT Users.UserID, ?, 0, ? FROM Users WHERE Users.username = ? LIMIT 1');
        $stmt->bind_param('sss', $accountName, $accountNumber, $user->username);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $stmt->close();
    }
    $conn->close();
}

/**
 * Creates an application for a new account
 * @param User $user the owner of the new account
 * @param string $accountName name of the account to be created
 */
function createAccountApplication(User $user, string $accountName) {
    $conn = connectToDatabase();

    $findAccountNumber = true;
    while ($findAccountNumber) {
        $accountNumber = generateAccountNumber();
        if (!getAccount($accountNumber)) {
            $findAccountNumber = false;
        }
    }

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $timestamp = date('Y-m-d H:i:s');

        $stmt = $conn->prepare('INSERT INTO AccountRequests (UserID, accountName, accountNumber, requestTimestamp) SELECT Users.UserID, ?, ?, ? FROM Users WHERE Users.username = ? LIMIT 1');
        $stmt->bind_param('ssss', $accountName, $accountNumber, $timestamp, $user->username);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $stmt->close();
    }
    $conn->close();
}

/**
 * Generates a pseudo-random string for use as an account number
 *
 * WARNING:
 * This function does not guarantee uniqueness in the account numbers it generates
 *
 * @return string the generated account number
 */
function generateAccountNumber() {
    try {
        $accountNumber = (string)random_int(100000000, 999999999);
    } catch (Exception $e) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    }
    $accumulator = 0;
    for ($i = 0; $i < 9; $i++) {
        $accumulator += ((int)$accountNumber[$i] * ($i % 2 ? 1 : 2)) % 10;
    }
    return $accountNumber . ($accumulator % 10);
}

/**
 * Checks if the account number is valid (not whether it's in use; use getAccount for that)
 * @param $accountNumber string the account number to check
 * @return bool whether the account number is valid
 */
function isAccountNumberValid(string $accountNumber) {
    if (strlen($accountNumber) != 10) {
        return false;
    }

    $accumulator = 0;
    for ($i = 0; $i < 9; $i++) {
        $accumulator += ((int)$accountNumber[$i] * ($i % 2 ? 1 : 2)) % 10;
    }

    return $accountNumber[-1] === (string)($accumulator % 10);
}

/**
 * Retrieves transfer history, optionally filtering for one or more accounts
 * @param Account[] $accounts
 * @return array transfers
 */
function getTransfers(array $accounts = NULL) {
    $conn = connectToDatabase();
    $transfers = array();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        if (empty($accounts)) {
            $stmt = $conn->prepare('SELECT T.transferTimestamp, T.transferValue, SA.AccountNumber AS Sender, RA.AccountNumber AS Receiver FROM Transfers T, Accounts SA, Accounts RA WHERE T.ReceiverID = RA.AccountID AND T.SenderID = SA.AccountID ORDER BY T.transferTimestamp DESC');

            if (!$stmt->execute()) {
                http_response_code(500);
                die('An unexpected error has occurred. Please try again later.');
            }
            $result = $stmt->get_result();
            $stmt->close();
            $resultArray = $result->fetch_all(MYSQLI_ASSOC);

            if (!empty($resultArray)) {
                array_push($transfers, ...$resultArray);
            }
        } else {
            foreach ($accounts as $acc) {
                $stmt = $conn->prepare('SELECT T.transferTimestamp, T.transferValue, SA.accountNumber AS Sender, RA.accountNumber AS Receiver, RA.accountNumber = ? AS deposit FROM Transfers T JOIN Accounts SA JOIN Accounts RA ON T.ReceiverID = RA.AccountID AND T.SenderID = SA.AccountID WHERE RA.accountNumber = ? OR SA.accountNumber = ?');
                $stmt->bind_param("sss", $acc->accountNumber, $acc->accountNumber, $acc->accountNumber);

                if (!$stmt->execute()) {
                    http_response_code(500);
                    die('An unexpected error has occurred. Please try again later.');
                }
                $result = $stmt->get_result();
                $stmt->close();
                $resultArray = $result->fetch_all(MYSQLI_ASSOC);

                if (!empty($resultArray)) {
                    array_push($transfers, ...$resultArray);
                }
            }
        }
    }
    $conn->close();
    return $transfers;
}

/**
 * Retrieves account applications
 * @param User|null $user user to filter for, leave NULL for all users
 * @param int|null $status status of applications to filter for, leave NULL for all statuses
 * @return array applications
 */
function getAccountApplications(User $user = NULL, int $status = NULL) {
    $conn = connectToDatabase();
    $applications = array();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        if (!is_null($user) && !is_null($status)) {
            $stmt = $conn->prepare('SELECT U.username, A.accountName, A.status, A.accountNumber, A.requestTimestamp FROM AccountRequests A, Users U WHERE A.UserID = U.UserID AND U.username = ? AND status = ? ORDER BY A.requestTimestamp DESC');
            $stmt->bind_param("si", $user->username, $status);
        } else if (!is_null($user)) {
            $stmt = $conn->prepare('SELECT U.username, A.accountName, A.status, A.accountNumber, A.requestTimestamp FROM AccountRequests A, Users U WHERE A.UserID = U.UserID AND U.username = ? ORDER BY A.requestTimestamp DESC');
            $stmt->bind_param("s", $user->username);
        } else if (!is_null($status)) {
            $stmt = $conn->prepare('SELECT U.username, A.accountName, A.status, A.accountNumber, A.requestTimestamp FROM AccountRequests A, Users U WHERE A.UserID = U.UserID AND status = ? ORDER BY A.requestTimestamp DESC');
            $stmt->bind_param("i", $status);
        } else {
            $stmt = $conn->prepare('SELECT U.username, A.accountName, A.status, A.accountNumber, A.requestTimestamp FROM AccountRequests A, Users U WHERE A.UserID = U.UserID ORDER BY A.requestTimestamp DESC');
        }

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $result = $stmt->get_result();
        $stmt->close();
        $resultArray = $result->fetch_all(MYSQLI_ASSOC);

        if (!empty($resultArray)) {
            array_push($applications, ...$resultArray);
        }
    }
    $conn->close();
    return $applications;
}

function getTransferDescription() {

}


/**
 * Logs out the current user
 */
function logOut() {
    session_unset();
    session_destroy();
}

/**
 * Sanitise user input
 * @param $data string user input data to sanitise
 * @return string sanitised input data
 */
function sanitiseInput(string $data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = strip_tags($data);
    $data = htmlspecialchars($data);
    return $data;
}
