<?php

$expected     = array('object $object',
                      'object &$objectR',
                      'object $objectDefault = null',
                      'function ($returnObject) : object { /**/ } ',
                     );

$expected_not = array('function ($returnAbject) : abject { /**/ } ',
                     );

?>