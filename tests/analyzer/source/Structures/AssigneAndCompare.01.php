<?php
if ($id = strpos($string, $needle) !== false) { 
    // $id now contains a boolean (true or false), but not the position of the $needle.
}

// probably valid comparison, as $found will end up being a boolean
if ($found = strpos($string, $needle) === false) { 
    doSomething();
}

// always valid comparison, with parenthesis
if (($id = strpos($string, $needle)) !== false) { 
    // $id now contains a boolean (true or false), but not the position of the $needle.
}

// Being a lone instruction, this is always valid : there is no double usage with if condition
$isFound = strpos($string, $needle) !== false;

?>