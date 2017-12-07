<?php

$expected     = array('\'/[0-9a-z]+/\'',
                      '\'#[0-9A-Z]+#is\'',
                      '\'#[0-8\' . \'A-Z]+#is\'',
                     );

$expected_not = array('\'[0-9\'.\'A-Z]+\'',
                     );

?>