<?php

require_once 'include/interest_rate_calculator.inc.php';
require_once 'include/accounts.inc.php';

$errors = array();
$fields = array("P" => NULL, "n" => NULL, "r" => NULL, "t" => NULL);
$readyToConvert = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($fields as $field => $value) {
        if (!isset($_POST[$field]) || empty($_POST[$field])) {
            $readyToConvert = false;
        } else {
            $fields[$field] = sanitiseInput($_POST[$field]);
        }
    }

    if ($readyToConvert) {
        $amount = calculate_interest(
            $fields['P'],
            $fields['r'],
            $fields['n'],
            $fields['t']
        );
    } else {
        $errors[] = 'Please ensure all fields are filled up';
    }
} else {
    $readyToConvert = false;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<!-- Meta Tags -->
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="Calculate your compound interest rate, offered freely by Premier Banking.">
    <?php include 'include/imports.inc.php' ?>
	<title>Premier Banking | Interest Rate Calculator</title>
</head>
<body>

<?php include "include/navbar.inc.php" ?>

<!-- Start of Header -->
<header class="hero">
	<div class="hero__content">
		<div class="container">
			<div class="row align-items-center">
				<div class="col-lg-6">
					<div class="banner__content mt-5 mb-2">
						<h1>Compound Interest Rate Calculator</h1>
						<p>Calculate your interest rate using compound interest rates!</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</header>

<!-- End of Header -->

<!-- Start of Main -->
<main class="container">
	<div class="row mb-5">
		<section class="col-md-6 col-sm-12 mt-5">
            <?php

            if (!empty($errors)) {
                foreach ($errors as $error) {
                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                }
            }

            ?>
			<form method="POST" id="interest-rate-calculator-form">
				<div class="form-group">
					<label for="principal">Principal amount</label>
					<div class="input-group">
						<div class="input-group-prepend">
							<span class="input-group-text">$</span>
						</div>
						<input type="number" step="0.01" min="0.01" class="form-control" id="principal"
						       value="<?php if (!is_null($fields['P'])) echo $fields['P'] ?>"
						       placeholder="Enter principal amount" name="P" required>
					</div>
				</div>

				<div class="form-group">
					<label for="rate">Interest rate</label>
					<div class="input-group">
						<input type="number" step="0.1" min="0.1" class="form-control" id="rate"
						       value="<?php if (!is_null($fields['r'])) echo $fields['r'] ?>"
						       placeholder="Enter interest rate" name="r" required>
						<div class="input-group-append">
							<span class="input-group-text">%</span>
						</div>
					</div>
				</div>

				<div class="form-group">
					<label for="times">Number of times compounded per year</label>
					<input type="number" min="0" class="form-control" id="times"
					       value="<?php if (!is_null($fields['n'])) echo $fields['n'] ?>"
					       placeholder="Enter times compounded per year" name="n" required>
				</div>

				<div class="form-group">
					<label for="years">Number of years</label>
					<input type="number" min="0.1" step="0.1" class="form-control" id="years"
					       value="<?php if (!is_null($fields['t'])) echo $fields['t'] ?>"
					       placeholder="Enter duration in years" name="t" required>
				</div>

				<button type="submit" class="btn btn__primary btn-lg">Calculate</button>
			</form>
		</section>
		<section class="col-md-6 col-sm-12 mt-5">
			<div id="interest-rate-calculator-amount-container">
				<p class="lead">Amount</p>
				<p class="h1"><?php
                    if ($readyToConvert) {
                        echo $amount;
                    } else {
                        echo '$0.00';
                    }
                    ?></p>
			</div>
		</section>
	</div>
</main>

<?php include "include/sessionTimeout.inc.php" ?>
<?php include "include/footer.inc.php" ?>
</body>

</html>
