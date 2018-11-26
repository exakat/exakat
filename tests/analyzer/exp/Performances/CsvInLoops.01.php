<?php

$expected     = array('fputcsv($f2, $r)',
                      'fputcsv($this->f2, $r)',
                     );

$expected_not = array('fputcsv($f, $r)',
                      'fputcsv($this->f, $r)',
                      'fputcsv($a->f, $r)',
                     );

?>