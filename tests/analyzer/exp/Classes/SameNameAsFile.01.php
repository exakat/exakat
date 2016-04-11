<?php

$expected     = array('trait SameNameAsFile',
                      'interface SameNameAsFile',
                      'class SameNameAsFile',
);

$expected_not = array('class classnotsamenameasfile',
                      'trait TraitReallyNotSameNameAsFile',
                      'interface TotallyNotSameNameAsFile',);

?>