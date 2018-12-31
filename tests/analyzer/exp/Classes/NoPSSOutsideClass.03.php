<?php

$expected     = array('array(\'static\', \'moo\')',
                      'array(\'PARENT\', \'moo\')',
                      'array(\'self\', \'moo\')',
                     );

$expected_not = array('array(\'Static\', \'boo\')',
                      'array(\'Self\', \'boo\')',
                      'array(\'Parent\', \'boo\')',
                      'array(\'static\', \'poo\')',
                      'array(\'self\', \'poo\')',
                      'array(\'PARENT\', \'Poo\')',
                     );

?>