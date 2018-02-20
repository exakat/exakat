<?php

// $x is an array
$x = range('a', 'z');
// $x is now a string
$x = implode('', $x);
$c = count($x); // $x is not an array anymore


// $letters is an array
$letters = range('a', 'z');
// $alphabet is a string
$alphabet = implode('', $letters);

// Here, $letters is cast by PHP, but the variable is changed.
if ($letters) { 
    $count = count($letters); // $letters is still an array 
}

?>