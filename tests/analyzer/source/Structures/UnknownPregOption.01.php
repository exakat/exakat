<?php
// B
preg_replace('#|&\#40;eis#B', 'b', $c);
// Ff
preg_match("#|&\#40;e${s}is#Ff", 'b', $c);

//a
preg_replace_callback("#|&\#40;e".$s."is#eimsuxADJSUXa", 'b', $c);
preg_replace_callback("#|&\#41;e".$s."is#eimsuxaADJSUX", 'b', $c);
preg_replace_callback("#|&\#42;e".$s."is#aeimsuxADJSUX", 'b', $c);

// All good
\preg_grep('(' . $a . '(!?=+)' . $c . ')sie', '$f', $i);
\preg_replace('(' . $b . '(!?=+)' . $d . ')SUD', '$g', $j);

?>