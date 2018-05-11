<?php

require_once("recaptchalib.php");

$publickey = "6LfWf-gSAAAAAHJua0n6b3h0xZfBryOQXXAGkQRk";
$privatekey = "6LfWf-gSAAAAAH8Crn7sGVYlSsugtYS7QEZP02DM";

echo recaptcha_get_html($publickey);

?>