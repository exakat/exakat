<?php

$expected     = array('class classnotsamenameasfile',
                      'trait TraitReallyNotSameNameAsFile',
                      'interface TotallyNotSameNameAsFile',
                      );

$expected_not = array('trait SameNameAsFile',
                      'interface SameNameAsFile',
                      'class SameNameAsFile',);

?>