<?php

$expected     = array('$$variable',
                      '${normal_variable}',
                     );

$expected_not = array('a',
                      '$variablevariable',
                      '$variable',
                      '$x',
                      '$normal_variable',
                     );

?>