<?php

$expected     = array('$select->from(\'foo\')->where(\'x = \' . $v)',
                      '$select->from(\'foo\')->where(\\"x = $v\\")',
                     );

$expected_not = array('$select->from(\'foo\')->where(\'x = 5\')',
                      '$select->from(\'foo\')->where([\'x\' => $v])',
                     );

?>