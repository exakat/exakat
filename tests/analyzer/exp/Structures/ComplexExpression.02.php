<?php

$expected     = array('explode("\\n", wordwrap($this->c, floor($this->d / imagefontwidth($this->e) + 2 * 33 - 4 - $x[\'b\' . \'c\'] + 1), "\\n"))',
                     );

$expected_not = array('explode("\\n", $b)',
                     );

?>