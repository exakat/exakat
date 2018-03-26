<?php

$expected     = array('switch ($withFallthroughCase) { /**/ } ',
                      'switch ($withFallthroughDefault) { /**/ } ',
                      'switch ($with2FallthroughCaseDefault) { /**/ } ',
                     );

$expected_not = array('switch ($withoutFallthrough) { /**/ } ',
                     );

?>