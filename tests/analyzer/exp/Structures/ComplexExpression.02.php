<?php

$expected     = array('explode("\n", wordwrap($this->c, floor($this->d / imagefontwidth($this->e)), "\n"))',
                     );

$expected_not = array('explode("\n", $b)',
                     );

?>