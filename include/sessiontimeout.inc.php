<?php
$time = $_SERVER['REQUEST_TIME'];
if (isset($_SESSION['LAST_ACTIVITY']) && ($time - $_SESSION['LAST_ACTIVITY'] > 60)) {
		header('Location: logout.php');
}
$_SESSION['LAST_ACTIVITY'] = $time; // update last activity time stamp
?>
