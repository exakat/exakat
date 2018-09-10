<?php
// init gnupg
$res = gnupg_init();
// not really needed. Clearsign is default
gnupg_setsignmode($res,GNUPG_SIG_MODE_CLEAR);
// add key with passphrase 'test' for signing
gnupg_addsignkey($res,"8660281B6051D071D94B5B230549F9DC851566DC","test");
// sign
$signed = gnupg_sign($res,"just a test");
echo $signed;

gnupg_logout();
?>