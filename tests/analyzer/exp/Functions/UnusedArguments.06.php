<?php

$expected     = array('function ($a) use ($W) { /**/ } ', 
                      'function ($a) use ($V) { /**/ } ', 
                      'function ($a) use ($X) { /**/ } '
                     );

$expected_not = array('function () use ($W) { /**/ } ', 
                      'function () use ($V) { /**/ } ', 
                      'function () use ($X) { /**/ } '
                     );

?>