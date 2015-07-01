<?php

$expected     = array('abstract class uselessAbstractClass',
                      'abstract class uselessEmptyAbstractClass',);

$expected_not = array('abstract class abstractClass',
                      'class normalClasssWithoutExtends',
                      'class abstractSubClass extends abstractClass');

?>