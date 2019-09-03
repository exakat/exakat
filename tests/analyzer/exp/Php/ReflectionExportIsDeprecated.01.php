<?php

$expected     = array('ReflectionFunction::export(\'foo\')',
                      'ReflectionFunction::export(\'foo\', true)',
                     );

$expected_not = array('(string) new ReflectionNotValid(\'foo\')',
                      '(new ReflectionFunction(\'foo\'))->export(\'foo\', true)',
                     );

?>