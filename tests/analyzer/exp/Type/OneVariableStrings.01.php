<?php

$expected     = array('${dollar_curly}',
                      '{$curly_dollar}',
                      '$array[3]',
                      '$varstring',
                      '$object->property',
                     );

$expected_not = array('no variable',
                      '$two $variables',
                     );

?>