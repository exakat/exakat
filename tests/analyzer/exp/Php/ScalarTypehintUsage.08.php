<?php

$expected     = array('object $objectDefault = null',
                      'object $object',
                      'object &$objectR',
                      'function returnObject( ) : object { /**/ } ',
                     );

$expected_not = array('function returnAbject() : abject { /**/ } ',
                     );

?>