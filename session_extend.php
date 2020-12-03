<?php
require_once 'include/accounts.inc.php';

$json = array();
$loggedIn = (bool)getAuthenticatedUser();

$json['has_expired'] = !$loggedIn;
if ($loggedIn) {
    $json['expires_in'] = $_SESSION['session_expiry'] - time();
}

echo json_encode($json);
?>