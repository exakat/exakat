<?php

//standard case
if ($noElse) {$z--;}

if ($withElse) {$a++;} else { $b++; }

// "elsif" are OK, as long as the last is not ELSE
if ($withElsifNoElse) {$a++;} elseif ($withElsifNoElse) { $b++; }

if ($withElsifAndElse) {$a++;} elseif ($withElsifAndElse) { $b++; } else { $c++;}


// "else if" is elsif
if ($withElseifNoElse) {$a++;} else if ($withElseifNoElse) { $b++; }

if ($withElseifAndElse) {$a++;} else if ($withElseifAndElse) { $b++; } else { $c++;}

//else if 
if ($withElseifNoElse) {$a++;} else {
    if ($withinElseAndWithElse) { $b++; } else { $c++; }
    $d++;
}

?>