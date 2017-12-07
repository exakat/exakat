<?php

$expected     = array('$this->withoutDefault',
                     );

$expected_not = array('$this->resetInAnotherMethod',
                      '$withDefaultAndIntact',
                      '$this->assignedWithVariable',
                      '$this->withDefaultButRedefined',
                      '$this->undefinedProperty',
                      '$this->arrayWithDefault[2]',
                     );

?>