<?php

$expected     = array('$this->b->load($a)'
                     );

$expected_not = array('$this->load($a)',
                      '$this->b->load( )',
                     );

?>