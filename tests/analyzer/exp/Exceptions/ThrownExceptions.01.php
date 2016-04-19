<?php

$expected     = array( 'class mySubException extends myException', 
                       'class myException extends Exception');

$expected_not = array('unknownException()',
                      'runtimeException()');

?>