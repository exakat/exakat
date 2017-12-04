<?php

$expected     = array('$this->getLocalStaticMethod( )',
                     );

$expected_not = array('$this->getLocalMethod( )',
                      '$b->getExternalMethod( )',
                     );

?>