<?php

$expected     = array('\a\constant\in\unset\space',
                      '\a\co$nstant\in\unset\space',
                      'cons$tant',
                      '\a\co$nstant\in\unset\space',);

$expected_not = array('a\constant\in\another\space',
                      '\a\constant\in\another\space');

?>