<?php

$expected     = array('$this->c[]',
                     );

$expected_not = array('$this->b[\'a\']',
                      '$this->a[\'a\']',
                      '$this->o[$name]',
                     );

?>