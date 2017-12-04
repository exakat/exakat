<?php

$expected     = array('strtolower($name)',
                      'ucfirst(strtolower($name))',
                      'strtolower($name)',
                      'ucfirst(strtolower($name))',
                     );

$expected_not = array('strtolower($last)',
                      'ucfirst(strtolower($last))',
                     );

?>