<?php

$expected     = array('Cake\\Utility\\Crypto\\Mcrypt as C',
                      'Cake\\Utility\\Crypto\\Mcrypt as Mcrypt2',
                      'Mcrypt2( )',
                      'C',
                     );

$expected_not = array('Cake\\Utility\\OtherNamesapceCrypto\\Mcrypt',
                     );

?>