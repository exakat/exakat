<?php

$expected     = array('class samenameasfile { /**/ } ',
                      'trait samenameasfile { /**/ } ',
                      'interface samenameasfile { /**/ } ',
                      'interface NotSameNameAsFile { /**/ } ',
                     );

$expected_not = array('trait SameNameAsFile { /**/ } ',
                      'interface SameNameAsFile { /**/ } ',
                      'class SameNameAsFile { /**/ } ',
                     );

?>