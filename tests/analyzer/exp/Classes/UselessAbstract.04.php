<?php

$expected     = array('abstract class uselessAbstractClass { /**/ } ',
                      'abstract class abstractClass { /**/ } ',
                     );

$expected_not = array('class normalClasssWithoutExtends { /**/ } ',
                      'class abstractSubClass extends abstractClass { /**/ } ',
                     );

?>