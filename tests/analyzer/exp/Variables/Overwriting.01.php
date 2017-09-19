<?php

$expected     = array('$m = static::M($m, $n)', 
                      '$k = apply_filters(\'E\', $k, $l)', 
                      '$m = "$nN" . O(\'P\', \'Q\') . "R$m"', 
                      '$m = L::M($m, $n)', 
                      '$o = implode(\'V\', $o)', 
                      '$b = mySort($b, \'G\', H)', 
                      '$b = array_slice($b, $c, $d, C)', 
                      '$b = B($b, \'J\', K)', 
                      '$p = str_replace(\'X\', \'Y\', $p)', 
                      '$e = $f . $g . $h . $e . $i . $j', 
                      '$m = self::M($m, $n)',
                      );

$expected_not = array('$o = (array) $o',
                     );

?>