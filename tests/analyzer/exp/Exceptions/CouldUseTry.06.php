<?php

$expected     = array('\\Reflector($a->b( ))',
                      'ReflectionProperty($a->b( ))',
                     );

$expected_not = array('new \\B\\Reflection($a->b( ))',
                      'new RedirectResponse($a->b( ))',
                     );

?>