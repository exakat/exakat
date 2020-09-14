<?php

$expected     = array('throw $g = new Exception(\'3\')',
                     );

$expected_not = array('throw $this->h = new Exception(\'1\')',
                      'throw $e = new Exception(\'2\')',
                     );

?>