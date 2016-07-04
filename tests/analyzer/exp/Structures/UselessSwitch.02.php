<?php

$expected     = array('switch ($a0->d) { /* cases */ }', 
                      'switch ($a1) { /* cases */ }');

$expected_not = array('switch ($a2) { /* cases */ }', 
                      'switch ($a3) { /* cases */ }', );

?>