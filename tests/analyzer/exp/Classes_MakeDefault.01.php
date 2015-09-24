<?php

$expected     = array('$this->withDefaultButRedefined',
                      '$this->withoutDefault',
                      '$this->undefinedProperty',
                      '$this->arrayWithDefault[2]');

$expected_not = array('$this->resetInAnotherMethod',
                      '$withDefaultAndIntact',
                      '$this->assignedWithVariable');

?>