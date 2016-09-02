<?php

$a = $_REQUEST;
unset($_GET);
unset($_POST['s']);
unset($_REQUEST['a']['b']);

$_SERVER = array();
$_ENV['a'] += 2;
$_FILES['b']['3'] .= 4;

?>