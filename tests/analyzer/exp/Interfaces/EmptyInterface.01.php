<?php

$expected     = array('interface emptyInterface { /**/ } ',
                      'interface emptyExtendingInterface extends emptyInterface { /**/ } ',
                      );

$expected_not = array('interface nonEmptyInterface { /**/ } ',
                      'interface nonEmptyInterface2 { /**/ } ',
                      'interface nonEmptyInterface3 { /**/ } ',
                      );

?>