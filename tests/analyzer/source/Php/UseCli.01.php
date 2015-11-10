<?php

$_REQUEST  = $_GET + $_POST + $_COOKIE;

// Borrowed from Drush.
function devel_verify_cli() {
  if (php_sapi_name() == 'cgi') {
    return (is_numeric($_SERVER['argc']) && $argc > 0);
  }

  return (php_sapi_name() == 'cli');
}

echo $_SERVER['REQUEST_URI'] + $_SERVER['OTHER_VARIABLE'];
?>