<?php
phpinfo();
include_once '2fa.php';
ini_set('sendmail_from', 'leekaixuan2001@gmail.com');

# Is the OS Windows or Mac or Linux
if (strtoupper(substr(PHP_OS, 0, 3) == 'WIN')) {
    $eol = "\r\n";
} elseif (strtoupper(substr(PHP_OS, 0, 3) == 'MAC')) {
    $eol = "\r";
} else {
    $eol = "\n";
}


# To Email Address
$emailaddress = "leekaixuan2001@gmail.com";
# Message Subject
$emailsubject = "Here's An Email" . date("Y/m/d H:i:s");
# Message Body
$body = <<<Email
<html lang="en">
<head>
<title>HTML email</title>
</head>
<body>
<p>This email contains HTML Tags!</p>
<table>
<tr>
<th>Firstname</th>
<th>Lastname</th>
</tr>
<tr>
<td>John</td>
<td>Doe</td>
</tr>
</table>
</body>
</html>
Email;

# Common Headers
$headers = 'From: Jonny <jon@example.com>' . $eol;
$headers .= 'Reply-To: Jonny <jon@example.com>' . $eol;
$headers .= 'Return-Path: Jonny <jon@example.com>' . $eol;     // these two to set reply address
//$headers .= "Message-ID:<" . $now . " TheSystem@" . $_SERVER['SERVER_NAME'] . ">" . $eol;
$headers .= "X-Mailer: PHP v" . phpversion() . $eol;           // These two to help avoid spam-filters
# Boundary for marking the split & Multitype Headers
$mime_boundary = md5(time());
$headers .= 'MIME-Version: 1.0' . $eol;
$headers .= "Content-Type: multipart/related; boundary=\"" . $mime_boundary . "\"" . $eol;
$msg = "";

# Text Version
$msg .= "--" . $mime_boundary . $eol;
$msg .= "Content-Type: text/plain; charset=iso-8859-1" . $eol;
$msg .= "Content-Transfer-Encoding: 8bit" . $eol;
$msg .= "This is a multi-part message in MIME format." . $eol;
$msg .= "If you are reading this, please update your email-reading-software." . $eol;
$msg .= "+ + Text Only Email from Genius Jon + +" . $eol . $eol;

# HTML Version
$msg .= "--" . $mime_boundary . $eol;
$msg .= "Content-Type: text/html; charset=iso-8859-1" . $eol;
$msg .= "Content-Transfer-Encoding: 8bit" . $eol;
$msg .= $body . $eol . $eol;

# Finished
$msg .= "--" . $mime_boundary . "--" . $eol . $eol;   // finish with two eol's for better security. see Injection.


# SEND THE EMAIL
mail($emailaddress, $emailsubject, $msg, $headers);

?>
<html lang="en">
<p>Hello</p>
</html>