<?php

//# Common Headers
//$headers = 'From: Jonny <jon@example.com>' . $eol;
//$headers .= 'Reply-To: Jonny <jon@example.com>' . $eol;
//$headers .= 'Return-Path: Jonny <jon@example.com>' . $eol;     // these two to set reply address
////$headers .= "Message-ID:<" . $now . " TheSystem@" . $_SERVER['SERVER_NAME'] . ">" . $eol;
//$headers .= "X-Mailer: PHP v" . phpversion() . $eol;           // These two to help avoid spam-filters
//# Boundary for marking the split & Multitype Headers
//$mime_boundary = md5(time());
//$headers .= 'MIME-Version: 1.0' . $eol;
//$headers .= "Content-Type: multipart/related; boundary=\"" . $mime_boundary . "\"" . $eol;
//$msg = "";
//
//# Text Version
//$msg .= "--" . $mime_boundary . $eol;
//$msg .= "Content-Type: text/plain; charset=iso-8859-1" . $eol;
//$msg .= "Content-Transfer-Encoding: 8bit" . $eol;
//$msg .= "This is a multi-part message in MIME format." . $eol;
//$msg .= "If you are reading this, please update your email-reading-software." . $eol;
//$msg .= "+ + Text Only Email from Genius Jon + +" . $eol . $eol;
//
//# HTML Version
//$msg .= "--" . $mime_boundary . $eol;
//$msg .= "Content-Type: text/html; charset=iso-8859-1" . $eol;
//$msg .= "Content-Transfer-Encoding: 8bit" . $eol;
//$msg .= $body . $eol . $eol;
//
//# Finished
//$msg .= "--" . $mime_boundary . "--" . $eol . $eol;   // finish with two eol's for better security. see Injection.

/**
 * Generates and returns a code for purpose of 2fa
 * @return string 2fa code
 */
function generate_2fa_code() {
    $length = 5;
    $randomBytes = openssl_random_pseudo_bytes($length);
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $result = '';
    for ($i = 0; $i < $length; $i++)
        $result .= $characters[ord($randomBytes[$i]) % $charactersLength];
    return $result;
}

/**
 * Verifies the email of a user
 * @param $user User the user to verify
 */
function verify_email(User $user) {
    $conn = connectToDatabase();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('INSERT INTO EmailVerification (UserID, code) SELECT UserID, ? FROM Users WHERE username = ? ON DUPLICATE KEY UPDATE code = ?');
        $code = generate_2fa_code();
        $stmt->bind_param("sss", $code, $user->username, $code);
        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }
        $stmt->close();
    }
    $conn->close();

    $url = 'https://api.mailgun.net/v3/premierbanking.tech/messages';
    $auth = base64_encode('api:22fc4d7ee2116d25e16256d342caea63-95f6ca46-697f0128');
    $emailHTML = <<<EMAIL
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans&display=swap" rel="stylesheet"> 
    <style>
        body {
            font-family: 'Public Sans', sans-serif;
        }
        
        img {
            width: 100%;
        }
        
        .email-logo {
            width: 10%;
        }
        
        .header-image {
            width: 20%;
            padding: 5% 0;
            margin: 0 auto;
            display: block;
        }
        
        .email-main {
            padding: 5% 30%;
            text-align: center;
        }
        
        code {
            font-size: 2em;
        }
    </style>
    <title>Email</title>
</head>
<body>
    <header>
        <nav>
            <div class="email-logo">
                <img alt="Premier Banking Logo" src="https://premierbanking.tech/assets/img/logo.png">
            </div>

        </nav>
        <div class="header-image-container">
            <img alt="" class="header-image" src="https://premierbanking.tech/assets/img/undraw_arrived_f58d.png">
        </div>
    </header>
    <main class="email-main">
        <h1>One last step</h1>
        <p>Hey $user->username, you're almost ready to start changing your financial life. Please enter the following code to verify your email.</p>
        <code>$code</code>
    </main>
</body>
</html>
EMAIL;

    $data = array('from' => 'Premier Banking <cs@premierbanking.tech>',
        'to' => $user->email,
        'subject' => 'Verify your email',
        'html' => $emailHTML);

    $options = array(
        'http' => array(
            'header' => "Authorization: Basic $auth\r\nContent-Type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    }
}

function verify_code(User $user, string $code) {
    $conn = connectToDatabase();

    if ($conn->connect_error) {
        http_response_code(500);
        die('An unexpected error has occurred. Please try again later.');
    } else {
        $stmt = $conn->prepare('SELECT code FROM EmailVerification WHERE UserID = (SELECT UserID FROM Users WHERE username = ?)');
        $stmt->bind_param('s', $user->username);

        if (!$stmt->execute()) {
            http_response_code(500);
            die('An unexpected error has occurred. Please try again later.');
        }

        $result = $stmt->get_result();
        $stmt->close();
    }

    while ($row = $result->fetch_assoc()) {
        if ($code === $row['code']) {
            $stmt = $conn->prepare('UPDATE Users SET isVerified = 1 WHERE username = ?');
            $stmt->bind_param('s', $user->username);

            if (!$stmt->execute()) {
                http_response_code(500);
                die('An unexpected error has occurred. Please try again later.');
            }
            $stmt->close();
            $conn->close();
            return true;
        }
    }
    $conn->close();
    return false;
}
