<?php

$expected     = array('($this->far == \'TL\') || ($this->far == \'TR\')',
                      '($this->far == \'T\') || ($this->far == \'TL\') || ($this->far == \'TR\')',
                     );

$expected_not = array('($this->fir == \'TL2\') || ($this->far == \'TR2\')',
                     );

?>