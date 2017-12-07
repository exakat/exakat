<?php

$expected     = array('interface missingImplementsInterface extends sessionhandlerinterface { /**/ } ',
                      'class missingImplements implements sessionhandlerinterface { /**/ } ',
                     );

$expected_not = array('interface mySessionHandlerInterface extends sessionhandlerinterface, SessionUpdateTimestampHandlerInterface { /**/ } ',
                      'class mySessionHandler implements sessionhandlerinterface, SessionUpdateTimestampHandlerInterface { /**/ } ',
                     );

?>