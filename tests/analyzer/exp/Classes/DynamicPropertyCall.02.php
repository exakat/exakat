<?php

$expected     = array('$object->$propertyname1[1]', 
                      '$object->$propertyname1[1][3]');

$expected_not = array('Classe::normalPropertyname1', 
                      '$object->normalPropertyname1',
                      'Classe::$propertyname2[2]', 
                      'Classe::$propertyname2[2][4]', 
                      );

?>