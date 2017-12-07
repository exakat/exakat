<?php

$expected     = array('global $aInC',
                      '$GLOBALS[\'aInD\']',
                     );

$expected_not = array('$GLOBALS[\'aInDOK\']',
                      'global $aInCOK',
                     );

?>