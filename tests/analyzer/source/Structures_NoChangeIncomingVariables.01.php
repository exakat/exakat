<?php

unset($_GET);
unset($_POST['s']);
unset($_REQUEST['a']['b']);

$_COOKIE = array();
$_ENV['a'] += 2;
$_FILES['b']['3'] .= 4;

?>