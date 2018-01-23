<?php
$ret = apache_getenv("SERVER_ADDR");
$ret = apache_env("SERVER_ADDR");
echo $ret;
?>