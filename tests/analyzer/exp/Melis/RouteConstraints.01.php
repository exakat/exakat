<?php

$expected     = array('\'route\' => \'MissingSome/AddingSome[/:moduleName]/pageid[/:pageid]/exclude-pageid[/:expageid]\'', 
                      'array(\'route\' => \'NoConstraints/module[/:xxx]/pageid\',  )'
                     );

$expected_not = array('\'route\' => \'GoodNumber/module[/:moduleName]/pageid[/:moduleName2]/exclude-pageid/\'', 
                     );

?>