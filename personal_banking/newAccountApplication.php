<?php
require_once '../include/accounts.inc.php';

$user = getAuthenticatedUser();

if (!$user) {
    header('Location: ../login.php');
    exit();
}
?>

<!DOCTYPE html>

<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Open a New account page">
	<title>Premier Banking | Open new Account</title>
    <?php include '../include/imports.inc.php' ?>
</head>
<body id="register-body">
<main id="register" class="h-100">
	<section class="container d-flex flex-column justify-content-start h-100">
		<div class="row justify-content-center">
			<div class="col-lg-7 col-xl-6">
				<div class="brand text-center mt-5">
					<a href="index.php">
						Premier Banking
					</a>
				</div>
			</div>
		</div>
		<div class="row justify-content-center">
			<div class="col-lg-7 col-xl-6">
				<div class="form-container text-center align-self-center">
					<form action="process_new_account.php" method="POST">
						<div class="form-title">
							<h1 class="title">Open new Account</h1>
							<p>Your application will be processed by our admins within 3 business days.</p>
						</div>

						<div class="form-group">
							<label for="account-name" class="text-muted">Account name</label>
							<input type="text" class="form-control" id="account-name"
							       placeholder="Enter your preferred name for this account"
							       name="name" aria-label="Account name" required>
						</div>

						<div class="form-group">
							<button class="form-btn" type="submit">Apply</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</section>

</main>

<?php include "../include/sessionTimeout.inc.php" ?>
</body>
</html>
