<?php

$expected     = array('$a->p->method($a)', 
                      '$this->p->method($this)',
                     );

$expected_not = array('$this->p->method($a)',
                      '$a->p->method($this)',
                     );

?>