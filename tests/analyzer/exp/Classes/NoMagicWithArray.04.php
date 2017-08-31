<?php

$expected     = array('$this->a[\'a\']',
                      '$this->b[\'a\']',
                      '$this->c[]',
                      );

$expected_not = array('$this->o[$name]',
                     );

?>