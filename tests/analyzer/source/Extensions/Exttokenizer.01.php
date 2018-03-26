<?php
$source = file_get_contents('example.php');
$tokens = token_get_all($source);
$tokens[T_TOKEN_FUTURE] = 2;
?>