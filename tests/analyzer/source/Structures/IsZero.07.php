<?php

$a = $t1 + (($q - $t1) % ($this->_base - $t1));

$a = $t2 + (($q - $t2) ** ($this->_base - $t2));

$a = $t3 + ($q - $t3);

// The following should be detected
//$a = $t4 - ($q + $t4);

//$a = $t5 - ($q + $x = $t5);


?>