<?php

$expected     = array('clone \'a\'', 
                      'clone True',
                     );

$expected_not = array('clone clone new stdclass( )',
                      'clone new stdclass( )',
                      'clone $this',
                     );

?>