<?php

$expected     = array('function foo(string &$string, int &$int, bool &$bool, chaine &$chaine) { /**/ } ',
                     );

$expected_not = array('string &$string',
                      'int &$int',
                      'bool &$bool',
                     );

?>