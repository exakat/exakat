<?php

$expected     = array('interface i { /**/ } ',
                     );

$expected_not = array('interface j { /**/ } ',
                      'interface k extends stringable { /**/ } ',
                      'interface kk extends k { /**/ } ',
                     );

?>