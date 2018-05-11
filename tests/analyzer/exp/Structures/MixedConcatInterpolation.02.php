<?php

$expected     = array('"{$b}c" . PHP_EOL',
                      '"{$b}c" . $a->b->c',
                     );

$expected_not = array('"{$b}c" . ($a ? $b : $c);',
                      '"{$b}c" . CONSTANT;',
                      '"{$b}c" . A::CONSTANT;',
                      '"{$b}c" . A::$member;',
                      '"{$b}c" . $object->$member;',
                      '"{$b}c" . $array[$member];',
                      '"{$b}c" . number_format($a, 2);',
                      '"{$b}c" . $a->{$b.\'c\'};',
                      '"{$b}c" . $a[\'b\'][\'c\'];',
                     );

?>