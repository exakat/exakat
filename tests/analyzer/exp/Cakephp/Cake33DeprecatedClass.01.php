<?php

$expected     = array('Mcrypt2( )',
                      'C',
                     );

$expected_not = array('Cake\\Utility\\Crypto\\Mcrypt as C',
                      'Cake\\Utility\\Crypto\\Mcrypt as Mcrypt2',
                      'Cake\\Utility\\OtherNamesapceCrypto\\Mcrypt',
                     );

?>