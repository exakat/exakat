<?php

$expected     = array('switch ($expr1) /**/ ');

$expected_not = array('switch ($expr2) /**/ ',
                      'switch ($expr3) /**/ ',
                      'switch ($nestedExpr) /**/ ');

?>