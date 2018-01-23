<?php

// e option
preg_replace( '*([^\p{Lu}_])([\p{Lu}])*e' . $c, $a, $b);

// e option
preg_replace( "*([^\p{Lu}_])([\p{Lu}])*e$c", $c, $d);

// e option
preg_replace( '*([^\p{Lu}_])([\p{Lu}])*e', $e, $f);

// NOT e option
preg_replace( '*([^\p{Lu}_])([\p{Lu}])*', $g, $h);

?>