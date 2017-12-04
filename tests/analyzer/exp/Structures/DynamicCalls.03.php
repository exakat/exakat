<?php

$expected     = array('$node->{$this->_rightindex}',
                      '$object->{$this->_leftindex}',
                      'new $ext(NULL, isset($this) ? $this : NULL)',
                      '${$a}',
                      '$$a',
                      '${$a}',
                      '$$a',
                     );

$expected_not = array('$a1',
                      'isset($this)',
                      '$this->parent[\'id\'];',
                     );

?>