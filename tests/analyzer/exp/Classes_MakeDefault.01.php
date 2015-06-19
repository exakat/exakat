<?php

$expected     = array('$this->withDefaultButRedefined',
                      '$this->withoutDefault',
                      '$this->undefinedProperty');

$expected_not = array('$this->resetInAnotherMethod',
                      '$withDefaultAndIntact',
                      '$this->assignedWithVariable');

?>