<?php

$expected     = array('abstract class uselessAbstractClass { /**/ } ',
                     );

$expected_not = array('abstract class abstractClassTrait { /**/ } ',
                      'abstract class abstractClass { /**/ } ',
                      'class normalClasssWithoutExtends { /**/ } ',
                      'class abstractSubClass extends abstractClass { /**/ } ',
                     );

?>