<?php

$expected     = array('undefinedFunction( )',
                      'definedMethodUsedAsFunction( )',
                     );

$expected_not = array('definedFunction( )',
                      'definedMethod( )',
                      'definedStaticMethod( )',
                     );

?>