<?php
require_once '../include/accounts.inc.php';

$user = getAuthenticatedUser();

if (!$user || !$user->admin) {
    http_response_code(401);
    exit();
}

$json = array();

if (!isset($_POST['requestNumber']) || empty($_POST['requestNumber']) || !isset($_POST['approval']) || empty($_POST['approval'])) {
    http_response_code(400);
    exit();
}

$accountNumber = sanitiseInput($_POST['requestNumber']);
$approve = sanitiseInput($_POST['approval']) === 'true';

$conn = connectToDatabase();
if ($conn->connect_error) {
    http_response_code(500);
    die('An unexpected error has occurred. Please try again later.');
} else {
    if ($approve) {
        $stmt = $conn->prepare('INSERT INTO Accounts (UserID, accountName, accountValue, accountNumber) SELECT AccountRequests.UserID, AccountRequests.accountName, 0, ? FROM AccountRequests WHERE AccountRequests.accountNumber = ? LIMIT 1');
        $stmt->bind_param('ss', $accountNumber, $accountNumber);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $stmt->close();

        $stmt = $conn->prepare('UPDATE AccountRequests SET status = 1 WHERE accountNumber = ?');
        $stmt->bind_param('s', $accountNumber);
        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $stmt->close();
    } else {
        $stmt = $conn->prepare('UPDATE AccountRequests SET status = 2 WHERE accountNumber = ?');
        $stmt->bind_param('s', $accountNumber);
        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $stmt->close();
    }


}
$conn->close();

$json['success'] = true;
echo json_encode($json);
