<?php

if (empty($_SERVER['HTTPS'])) {
    http_response_code(301);
    header('Location: ' . 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
    exit();
}

session_set_cookie_params(1200, '/', $_SERVER['HTTP_HOST'], true, true);
session_name('session');
session_start();

/**
 * Class User
 * Encapsulates user information
 */
class User {
    public $username;
    public $firstName;
    public $lastName;
    public $email;

    public function __construct() {

    }
}


/**
 * Attempts to connect to the database
 * @return mysqli connection object
 */
function getConnectionToDb() {
    $config = parse_ini_file('../../private/db-config.ini');
    return new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
}

/**
 * Attempts to register a member
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
function registerUser($user, $hashed_password) {
    $conn = getConnectionToDb();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occured. Please try again later.');
    } else {
        $stmt = $conn->prepare('INSERT INTO Users (username, firstName, lastName, email, password) VALUES (?,?,?,?,?);');
        $stmt->bind_param('sssss', $user->username, $user->firstName, $user->lastName, $user->email, $hashed_password);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occured. Please try again later.');
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
function authenticateUser($email, $password) {
    $authenticated = false;
    $conn = getConnectionToDb();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occured. Please try again later.');
    } else {
        $stmt = $conn->prepare('SELECT username, firstName, lastName, password FROM Users WHERE email = ?');
        $stmt->bind_param('s', $email);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occured. Please try again later.');
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
function isUser($email) {
    $member = false;
    $conn = getConnectionToDb();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occured. Please try again later.');
    } else {
        $stmt = $conn->prepare('SELECT firstName FROM Users WHERE email = ?;');
        $stmt->bind_param('s', $email);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occured. Please try again later.');
        }

        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $member = true;
        }

        $stmt->close();
    }

    $conn->close();
    return $member;
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
 * Logs out the current user
 */
function logOut() {
    session_unset();
    session_destroy();
}


?>