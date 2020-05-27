<?php

$expected     = array('$this->c',
                      '$this->e2',
                      '$this->f',
                     );

$expected_not = array('$this->b',
                      '$this->d',
                      '$THIS->f',
                     );

?>