<?php
// new class
$gnupg = new gnupg();
// not really needed. Clearsign is default
$gnupg->setsignmode(gnupg::SIG_MODE_CLEAR);
// add key with passphrase 'test' for signing
$gnupg->addsignkey("8660281B6051D071D94B5B230549F9DC851566DC","test");
// sign
$signed = $gnupg->sign("just a test");
echo $signed;

$gnupg = new gnupg\gnupg();

?>