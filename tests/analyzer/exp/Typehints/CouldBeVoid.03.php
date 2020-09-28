<?php

$expected     = array('function bary($g) { /**/ } ',
                      'abstract function barx($g) ;',
                     );

$expected_not = array('function bary($g) : void { /**/ } ',
                      'abstract function bary($g) ; ',
                     );

?>