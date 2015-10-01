<?php

$expected     = array('$node->{$this->_rightindex}', 
                      '$object->{$this->_leftindex}', 
                      '${$a}->m(\'id\')', 
                      '$$a->m(\'id\')', 
                      '$$a->parent[\'id\']', 
                      '${$a}->parent[\'id\']', 
                      'new $ext(NULL, isset($this) ? $this : NULL)', 
                      '${$a}', 
                      '$$a', 
                      '${$a}', 
                      '$$a'
);

$expected_not = array('$a1');

?>