<?php

$expected     = array('foreach($this->B as &$a) { /**/ } ',
                      'foreach($this->B as $b) { /**/ } ',
                     );

$expected_not = array('foreach($this->B as &$d) { /**/ } ',
                      'foreach($this->B as $c) { /**/ } ',
                     );

?>