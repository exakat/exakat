<?php

// $x is an array
$x = range('a', 'z');
// $x is now a string
$x = join('', $x);
$c = count($x); // $x is not an array anymore


// $letters is an array
$letters = range('a', 'z');
// $alphabet is a string
$alphabet = join('', $letters);

// Here, $letters is cast by PHP, but the variable is changed.
if ($letters) { 
    $count = count($letters); // $letters is still an array 
}

?>