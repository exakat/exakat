<?php

$expected     = array('\'\\a\\constant\\in\\unset\\space\'',
                      '\'\\a\\co$nstant\\in\\unset\\space\'',
                      '\'cons$tant\'',
                     );

$expected_not = array('a\\constant\\in\\another\\space',
                      '\\a\\constant\\in\\another\\space',
                     );

?>