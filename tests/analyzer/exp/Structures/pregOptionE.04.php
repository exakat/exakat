<?php

$expected     = array('preg_replace(\'*([^\\p{Lu}_])([\\p{Lu}])*e\', $e, $f)',
                     );

$expected_not = array('preg_replace(\'*([^\\p{Lu}_])([\\p{Lu}])*e\' . $c, $a, $b)',
                      'preg_replace("*([^\\p{Lu}_])([\\p{Lu}])*e$c", $c, $d)',
                      'preg_replace( \'*([^\\p{Lu}_])([\\p{Lu}])*\', $g, $h)',
                     );

?>