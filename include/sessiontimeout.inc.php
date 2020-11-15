<?php
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 60)) {
		header('Location: logout.php');
		exit();
}
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp
?>
