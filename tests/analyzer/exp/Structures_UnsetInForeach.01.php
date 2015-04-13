<?php

$expected     = array('foreach($a as $b => &$unsetArrayC) ;',
                      'foreach($a as $unsetArrayB => $c) ;',
                      'foreach($a as &$unsetArrayC) ;',
                      'foreach($a as $b => $unsetC) ;',
                      'foreach($a as $b => &$unsetRefC) ;',
                      'foreach($a as $unsetB => $c) ;',
                      'foreach($a as $unsetC) ;',
                      'foreach($a as &$unsetRefC) ;',
);

$expected_not = array();

?>