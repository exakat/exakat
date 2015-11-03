<?php

ob_start();
echo "Hello\n";

setcookie("cookiename", "cookiedata");

ob_end_flush();

?>