<?php

$expected     = array( 'Classe::$propertyname2[2]', 
                       '$object->$propertyname1[1]',
                       'Classe::$propertyname2[2][4]', 
                       '$object->$propertyname1[1][3]');

$expected_not = array('Classe::normalPropertyname1[2]', 
                       '$object->normalPropertyname1[1]');

?>