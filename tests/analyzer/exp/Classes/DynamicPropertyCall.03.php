<?php

$expected     = array('Classe::$$propertyname2[2]',
                      '$object->{$propertyname1}',
                      '$object->{$propertyname1 . \'4\'}',
                     );

$expected_not = array('Classe::$propertyname3',
                     );

?>