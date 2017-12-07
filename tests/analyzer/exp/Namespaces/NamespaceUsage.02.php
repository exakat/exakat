<?php

$expected     = array('namespace A;',
                      'namespace B;',
                      'namespace C\\D\\R;',
                     );

$expected_not = array('namespace\\D',
                      'namespace',
                      '\\D',
                     );

?>