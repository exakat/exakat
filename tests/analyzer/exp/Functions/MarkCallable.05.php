<?php

$expected     = array('\'a\', \'b\'', 
                      '$c, \'d\'', 
                      '$e, \'f\'', 
                      '$g, \'$h\'', 
                      '$sssh4, \'sssh4\'', 
                      '$sssh5, \'sssh5\'', 
                      '$sssh6, \'sssh6\'', 
                      '$k, \'l\'', 
                      '$i, \'j\'', 
);

$expected_not = array('last',
                      'a31',
                      'sssh0',
                      'sssh1',
                      'sssh2',
                      'sssh3'
                      );

?>