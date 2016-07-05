<?php

$expected     = array('abstract class uselessAbstractClass { /**/ } ',
                      );

$expected_not = array('class normalClasssWithoutExtends { /**/ } ',
                      'abstract class abstractClass { /**/ } ',
                      'class abstractSubClass extends abstractClass { /**/ } ');

?>