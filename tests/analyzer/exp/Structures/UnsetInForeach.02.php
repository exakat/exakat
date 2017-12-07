<?php

$expected     = array('foreach($this->B as &$a) { /**/ } ',
                      'foreach($this->B as $b) { /**/ } ',
                      'foreach($this->B as $c) { /**/ } ',
                     );

$expected_not = array('foreach($this->B as &$d) { /**/ } ',
                     );

?>