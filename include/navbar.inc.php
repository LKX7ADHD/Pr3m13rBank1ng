<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/include/accounts.inc.php';

$user = getAuthenticatedUser();

if (!$user) {
    include $_SERVER['DOCUMENT_ROOT'] . '/include/home.navbar.inc.php';
} elseif(!$user->admin) {
    include $_SERVER['DOCUMENT_ROOT'] . '/include/personalBanking.navbar.inc.php';
} else {
    include $_SERVER['DOCUMENT_ROOT'] . '/include/admin.navbar.inc.php';
}