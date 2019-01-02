<?php

$expected     = array('fputcsv(self::$f2, $r)',
                      'fputcsv(self::$f4, $r)', 
                      'fputcsv($f2, $r)',
                      );

$expected_not = array('fputcsv($f, $r)',
                      'fputcsv(self::f, $r)',
                      'fputcsv(self::f3, $r)',
                      'fputcsv($a->f, $r)',
                     );

?>