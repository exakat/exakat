<?php

$expected     = array('foreach($a as $unsetC) { /**/ } ',
                      'foreach($a as $unsetB => $c) { /**/ } ',
                      'foreach($a as &$unsetRefC) { /**/ } ',
                      'foreach($a as $b => $unsetC) { /**/ } ',
                      'foreach($a as $b => &$unsetRefC) { /**/ } ',
                     );

$expected_not = array('foreach($a as &$unsetArrayC) { /**/ } ',
                      'foreach($a as $b => &$unsetArrayC) { /**/ } ',
                      'foreach($a as $unsetPropC2) { /**/ } ',
                      'foreach($a as $b => $unsetPropC2) { /**/ } ',
                      'foreach($a as $unsetArrayB => $c) { /**/ } ',
                     );

?>