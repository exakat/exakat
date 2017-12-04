<?php

$expected     = array('$e->getApplication( )->getServiceManager( )->get(\'Zend\\Session\\SessionManager\')',
                     );

$expected_not = array('$serviceManager->get(\'Zend\\Session\\NotSessionManager\')',
                     );

?>