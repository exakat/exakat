<?php

$expected     = array('global $onlyInXWithGlobal',
                     );

$expected_not = array('global $inXAndGlobal',
                      'global $inXAndYInverted',
                      'global $inXAndYWithGlobals',
                      'global $explicitInGlobal',
                     );

?>