<?php

$expected     = array('$object->$propertyname1',
                      '$object->$propertyname1',
                     );

$expected_not = array('Classe::normalPropertyname1',
                      '$object->normalPropertyname1',
                      'Classe::$propertyname2[2]',
                      'Classe::$propertyname2[2][4]',
                     );

?>