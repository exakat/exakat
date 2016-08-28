<?php

preg_replace( '*([^\p{Lu}_])([\p{Lu}])*e' . $c, $a, $b);

preg_replace( "*([^\p{Lu}_])([\p{Lu}])*e$c", $c, $d);

preg_replace( '*([^\p{Lu}_])([\p{Lu}])*e', $e, $f);

preg_replace( '*([^\p{Lu}_])([\p{Lu}])*', $g, $h);

?>