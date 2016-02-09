<?php

$expected     = array('Classe::$$propertyname2',
                      '$object->{$propertyname1}');

$expected_not = array('$object->{$propertyname1 . \'4\'}');

?>