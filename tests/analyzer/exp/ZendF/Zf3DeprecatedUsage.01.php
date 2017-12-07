<?php

$expected     = array('Zend\\EventManager\\SharedEventAggregateAwareInterface',
                      'Zend\\EventManager\\ProvidesEvents',
                      'Zend\\Authentication\\Adapter\\DbTable( )',
                      'Zend\\Db\\Sql::JOIN_OUTER_LEFT',
                      '$a->allowEmpty',
                      '$b->setLibOption( )',
                     );

$expected_not = array('Zend\\Db\\Sql::JOIN_LEFT',
                     );

?>