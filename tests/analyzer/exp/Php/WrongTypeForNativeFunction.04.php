<?php

$expected     = array('substr($this->pInt, 0, 1)',
                     );

$expected_not = array('substr($this->pString, 0, 1)',
                      'substr($this->pVoid, 0, 1)',
                     );

?>