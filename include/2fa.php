<?php

//# To Email Address
//$emailaddress = "leekaixuan2001@gmail.com";
//# Message Subject
//$emailsubject = "Here's An Email" . date("Y/m/d H:i:s");
//# Message Body
//$body = <<<Email
//<html lang="en">
//<head>
//<title>HTML email</title>
//</head>
//<body>
//<p>This email contains HTML Tags!</p>
//<table>
//<tr>
//<th>Firstname</th>
//<th>Lastname</th>
//</tr>
//<tr>
//<td>John</td>
//<td>Doe</td>
//</tr>
//</table>
//</body>
//</html>
//Email;
//
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
//
//
//# SEND THE EMAIL
//mail($emailaddress, $emailsubject, $msg, $headers);
//

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

function send_email($email, $username) {
    $url = 'https://api.mailgun.net/v3/premierbanking.tech/messages';

    $auth = base64_encode('api:22fc4d7ee2116d25e16256d342caea63-95f6ca46-697f0128');
    $code = generate_2fa_code();
    $emailHTML = <<<EMAIL
<html lang="en">
<h1>Big text</h1>
<em>Italicised</em>
<p>Hello $username</p>
<p>Please enter the following code to activate your account.</p>
<code>$code</code>
</html>
EMAIL;

    $data = array('from' => 'Premier Banking <cs@premierbanking.tech>',
        'to' => $email,
        'subject' => 'test',
        'html' => $emailHTML);

    $options = array(
        'http' => array(
            'header' => "Authorization: Basic $auth",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) {
    	echo 'Error: Please try again.';
    }

    var_dump($result);
}

send_email('leekaixuan2001@gmail.com', 'kx');
