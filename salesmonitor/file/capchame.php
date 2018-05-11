<?php

//$publickey = "6LfWf-gSAAAAAHJua0n6b3h0xZfBryOQXXAGkQRk";
$privatekey = "6LfWf-gSAAAAAH8Crn7sGVYlSsugtYS7QEZP02DM";

// fajar.monitor.com

$changefield=$_POST["changefield"];
$responsefield=$_POST["responsefield"];

require_once("recaptchalib.php");

//$privatekey = "[yourprivatekeyhere]";
$resp = recaptcha_check_answer ($privatekey,
         $_SERVER["REMOTE_ADDR"],
         $changefield,
         $responsefield);

if (!$resp->is_valid) {
    // What happens when the CAPTCHA was entered incorrectly
    echo "fail";
} else {
    echo "success";
}

?>