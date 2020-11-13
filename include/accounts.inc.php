<?php

// TODO: RE-ENABLE AFTER
//if (empty($_SERVER['HTTPS'])) {
//    http_response_code(301);
//    header('Location: ' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
//    exit();
//}
//session_set_cookie_params(1200, '/', $_SERVER['HTTP_HOST'], true, true);

// Using unsecure cookies during development
session_set_cookie_params(1200, '/', $_SERVER['HTTP_HOST']);
session_name('session');
session_start();


/**
 * Encapsulates user information
 */
class User {
    public $username;
    public $firstName;
    public $lastName;
    public $email;
}

/**
 * Encapsulates account information
 */
class Account {
    public $accountName;
    public $accountNumber;
    public $user;
    public $balance;

    public function getBalanceRepresentation() {
        $n = (strlen($this->balance) - 1) % 3 + 1;
        $representation = '$' . substr($this->balance, 0, $n);

        for ($i = 0; $i < ceil(strlen($this->balance) / 3) - 2; $i++) {
            $representation .= ',' . substr($this->balance, $n + 3 * $i, 3);
        }

        $representation .= substr($this->balance, -3);
        return $representation;
    }
}

/**
 * Attempts to connect to the database
 * @return mysqli connection object
 */
function connectToDatabase() {
//    TODO: SWITCH BACK TO AWS METHOD AFTER HEROKU DEVELOPMENT
//    $config = parse_ini_file('../../private/db-config.ini');
//    return new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

//    Heroku method
    $servername = getenv('heroku_db_servername');
    $username = getenv('heroku_db_username');
    $password = getenv('heroku_db_password');
    $dbname = getenv('heroku_db_dbname');

    return new mysqli($servername, $username, $password, $dbname);
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
        $stmt = $conn->prepare('SELECT username, firstName, lastName, password FROM Users WHERE email = ?');
        $stmt->bind_param('s', $email);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        }

        $stmt->close();
    }

    $conn->close();

    if (isset($row) && password_verify($password, $row['password'])) {
        $authenticated = true;

        $user = new User();
        $user->username = $row['username'];
        $user->firstName = $row['firstName'];
        $user->lastName = $row['lastName'];
        $user->email = $email;

        $_SESSION['user'] = $user;
    }

    $row = '';
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
        if ($result->num_rows > 0) {
            $isRegistered = true;
        }

        $stmt->close();
    }

    $conn->close();
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
        if ($result->num_rows > 0) {
            $isRegistered = true;
        }

        $stmt->close();
    }

    $conn->close();
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
 * @return array<Account> accounts
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
        while ($row = $result->fetch_assoc()) {
            $account = new Account();
            $account->user = $user;
            $account->accountName = $row['accountName'];
            $account->balance = $row['accountValue'];
            $account->accountNumber = $row['accountNumber'];

            $accounts[] = $account;
        }

        $stmt->close();
    }

    $conn->close();
    return $accounts;
}

/**
 * Retrieves account details
 * @param string $accountNumber
 * @return Account|false the account details, or false if account number does not belong to any account
 */
function getAccount(string $accountNumber) {
    $account = false;
    $conn = connectToDatabase();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('SELECT Accounts.accountName, Accounts.accountValue, Users.UserID FROM Accounts INNER JOIN Users ON Accounts.UserID=Users.UserID WHERE Accounts.accountNumber = ?');
        $stmt->bind_param('s', $accountNumber);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }

        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $account = new Account();
            $account->user = $row['UserID'];
            $account->accountName = $row['accountName'];
            $account->balance = $row['accountValue'];
            $account->accountNumber = $accountNumber;
        }

        $stmt->close();
    }

    $conn->close();
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
    while($findAccountNumber) {
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
 * Generates a pseudo-random string for use as an account number
 *
 * WARNING:
 * This function does not guarantee uniqueness in the account numbers it generates
 *
 * @return string the generated account number
 */
function generateAccountNumber() {
    try {
        $accountNumber = (string)random_int(10000000, 99999999);
    } catch (Exception $e) {
        die('An unexpected error has occurred. Please try again later.');
    }
    $accumulator = 0;

    for ($i = 0; $i < 8; $i++) {
        $accumulator += ((int)$accountNumber[$i] * (17 ** $i)) % 17;
    }

    return $accountNumber . sprintf('%02d', $accumulator % 17);
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

?>
