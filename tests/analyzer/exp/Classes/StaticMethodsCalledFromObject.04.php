<?php

$expected     = array('$this->getLocalStaticMethod( )',
                      '$b->getExternalMethod( )',
                     );

$expected_not = array('$this->getLocalMethod( )',
                      '$c->getUndefinedMethod( )',
                     );

?>