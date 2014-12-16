<?php

$expected     = array('while (strtolower($fp, 1000, "\\t", \'"\')) { /**/ } ');

$expected_not = array('while (a::b($fp, 1000, "\\t", \'"\')) { /**/ } ',
                      'while ($c->d($fp, 1000, "\\t", \'"\')) { /**/ } ',
                      'while (fgetcsv($fp, 1000, "\\t", \'"\')) { /**/ } ');

?>