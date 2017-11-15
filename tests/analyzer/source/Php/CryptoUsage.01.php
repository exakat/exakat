<?php

$hash     = md5($password);
$checksum = md5_file($filePath);

print $password;

?>