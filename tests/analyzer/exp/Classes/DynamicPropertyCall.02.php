<?php

$expected     = array(  'Classe::$propertyname2', 
                        'Classe::$propertyname2', 
                        '$object->$propertyname1',  
                        '$object->$propertyname1');

$expected_not = array('Classe::normalPropertyname1', 
                       '$object->normalPropertyname1');

?>