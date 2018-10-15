<?php

$expected     = array('$t3 + ($q - $t3)',
                     );

$expected_not = array('$t1 + (($q - $t1) % ($this->_base - $t1))',
                      '$t2 + (($q - $t2) ** ($this->_base - $t2))',
                     );

?>