<?php

$expected     = array('$this->b[\'a\']',
                      '$this->c[]',
                      );

$expected_not = array('$this->a[\'a\']',
                      '$this->o[$name]',
                     );

?>