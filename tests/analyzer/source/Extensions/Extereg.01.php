<?php
// Returns true if "abc" is found anywhere in $string.
ereg("abc", $string);            

// Returns true if "abc" is found at the beginning of $string.
ereg("^abc", $string);

// Returns true if "abc" is found at the end of $string.
ereg("abc$", $string);

// Returns true if client browser is Netscape 2, 3 or MSIE 3.
eregi("(ozilla.[23]|MSIE.3)", $_SERVER["HTTP_USER_AGENT"]);

// Places three space separated words into $regs[1], $regs[2] and $regs[3].
ereg("([[:alnum:]]+) ([[:alnum:]]+) ([[:alnum:]]+)", $string, $regs); 

// Put a <br /> tag at the beginning of $string.
$string = ereg_replace("^", "<br />", $string); 

// Put a <br /> tag at the end of $string.
$string = ereg_replace("$", "<br />", $string); 

// Get rid of any newline characters in $string.
$string = ereg_replace("\n", "", $string);

$string = ereg_split("\n", "", $string);

?>