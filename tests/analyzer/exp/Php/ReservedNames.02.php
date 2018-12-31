<?php

$expected     = array('$string',
                     );

$expected_not = array('$b',
                      '${$b}',
                      '$$s',
                      '$s',
                      '$php',
                      '$_post',
                     );

?>