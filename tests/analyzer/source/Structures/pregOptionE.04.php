<?php

// Preg_replace without /e
preg_replace('#|&\#40;eis#si', 'b', $c);
preg_replace("#|&\#40;e${s}is#si", 'b', $c);
preg_replace("#|&\#40;e".$s."is#si", 'b', $c);

// /e first
preg_replace('#|&\#40;eis#esi', 'b', $c);
preg_replace("#|&\#40;e${s}is#esi", 'b', $c);
preg_replace("#|&\#40;e".$s."is#esi", 'b', $c);

// /e middle
preg_replace('#|&\#40;eis#sei', 'b', $c);
preg_replace("#|&\#40;e${s}is#sei", 'b', $c);
preg_replace("#|&\#40;e".$s."is#sei", 'b', $c);

// /e last
preg_replace('#|&\#40;eis#sie', 'b', $c);
preg_replace("#|&\#40;e${s}is#sie", 'b', $c);
preg_replace("#|&\#40;e".$s."is#sie", 'b', $c);

preg_replace("/echo '\/\*%%SmartyNocache/", '', $b[0]);

preg_replace('#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#si', '', $x);
preg_replace('#src=.*?(?:(?:alert|prompt|confirm|eval)(?:\(|&\#40;)|javascript:|livescript:|mocha:|charset=|window\.|document\.|\.cookie|<script|<xss|base64\s*,)#sie', '', $x);

\preg_replace('(' . $a . '(!?=+)' . $c . ')', '$f', $i);
\preg_replace('(' . $b . '(!?=+)' . $d . ')e', '$g', $j);

?>