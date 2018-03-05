<?php

$expected     = array('$staticVariable',
                     );

$expected_not = array('$staticProperty',
                      '$noStaticPrivateProperty',
                      '$noStaticPublicProperty',
                      '$noStaticProtectedProperty',
                      'x',
                     );

?>