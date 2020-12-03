<?php
require_once 'include/accounts.inc.php';
require_once 'include/2fa.inc.php';

$user = $_SESSION['user_to_verify'];
$errors = array();

if (!isset($user)) {
    header('Location: ../login.php');
    exit();
}

if (isset($_POST['code']) && !empty($_POST['code'])) {
    $code = sanitiseInput($_POST['code']);
    if (verify_code($user, $code)) {
        createAccount($user, 'Basic Account');
        $_SESSION['user'] = $user;

        unset($_SESSION['user_to_verify']);

        if ($user->admin) {
            header("Location: /admin/");
            exit();
        } else {
            header("Location: /personal_banking/");
            exit();
        }
    } else {
        $errors[] = 'Invalid code';
    }
} else if (isset($_SESSION['request_code']) && $_SESSION['request_code']) {
    verify_email($user);
    $_SESSION['request_code'] = false;
}
?>

    <!DOCTYPE html>

    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="Verify your email to continue managing your accounts with Premier Banking.">
        <title>Premier Banking | Verify Email</title>
        <?php include 'include/imports.inc.php' ?>
    </head>
    <body id="register-body">
    <main id="register" class="h-100">
        <section class="container d-flex flex-column justify-content-start h-100">
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-6">
                    <div class="brand text-center mt-5">
                        <a href="/">
                            Premier Banking
                        </a>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-6">
                    <div class="form-container text-center align-self-center">
                        <form method="POST">
                            <div class="form-title">
                                <h1 class="title">Verify Email</h1>
                                <p>An email was sent to <b><?php echo $user->email ?></b> with your verification code.
                                    Can&apos;t find it? It may be in your spam.</p>
                            </div>

                            <?php
                            if (!empty($errors)) {
                                foreach ($errors as $error) {
                                    echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
                                }
                            }
                            ?>

                            <div class="form-group">
                                <label for="code" class="text-muted">Verification code</label>
                                <input type="text" class="form-control" id="code"
                                       placeholder="Enter the verification code" name="code"
                                       aria-label="Verification code" autocomplete="one-time-code" required>
                            </div>

                            <div class="form-group">
                                <button class="form-btn" type="submit">Verify email</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <?php include "include/sessionTimeout.inc.php" ?>
    </body>
    </html>
