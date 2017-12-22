<?php

$expected     = array('Set2( )',
                      'Set4',
                      'C',
                     );

$expected_not = array('Cake\\Utility\\OtherNamespaceCrypto\\Set',
                      'Cake\\Utility\\Set as C',
                      'Cake\\Utility\\Set as Set4',
                      'Cake\\Utility\\Set as Set2',
                      'Set3( )',
                     );

?>