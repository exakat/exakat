<?php

$expected     = array('$this->undefinedProperty',
                     );

$expected_not = array('$this->property',
                      '$this->property2',
                      '$this->property3',
                      '$this->$dynamicProperty',
                     );

?>