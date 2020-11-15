<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/include/accounts.inc.php';

if (getAuthenticatedUser()) {
    include $_SERVER['DOCUMENT_ROOT'] . '/include/personalBanking.navbar.inc.php';
} else {
    include $_SERVER['DOCUMENT_ROOT'] . '/include/home.navbar.inc.php';
}
?>