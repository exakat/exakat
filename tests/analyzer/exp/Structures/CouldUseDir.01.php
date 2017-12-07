<?php

$expected     = array('\\dirname(__FILE__)',
                      'DIRname(__FILE__)',
                     );

$expected_not = array('diRName(__FILE__)',
                      'basename(__FILE__)',
                     );

?>