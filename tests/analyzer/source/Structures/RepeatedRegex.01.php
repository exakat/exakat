<?php
// Regex used several times, at least twice.
preg_match('/twice/i', $_GET['x']);
preg_match('/twice/i', $row['name']);

preg_match('/three times/i', $_GET['x']);
preg_match_all('/three times/i', $row['name']);
preg_match('/three times/i', $GLOBALS['x']);

preg_match('/three times/i', $_GET['x']);
preg_replace('/three times/i', $row['name']);
preg_replace_callback('/three times/i', $GLOBALS['x']);

// This regex is dynamically built, so it is not reported.
preg_match('/^circle|^'.$x.'$/i', $string);

// This regex is used once, so it is not reported.
preg_match('/^circle|^square$/i', $string);

?>