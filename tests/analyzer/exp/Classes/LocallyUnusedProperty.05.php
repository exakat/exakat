<?php

$expected     = array('private $unused = array(\'H\' => 2)');

$expected_not = array('private $f = AC', 
                      'private $b = array(\'D\' => array(\'E\' => \'F\'), \'G\' => array(\'H\' => \'I\', \'J\' => \'I\', \'L\' => \'M\', \'N\' => \'O\', \'P\' => \'Q\', \'R\' => \'S\', \'T\' => \'U\', \'V\' => \'W\', \'X\' => \'I\', \'Z\' => \'AA\',  ))', 
                      'private $unused = array(\'H\' => 2)', 
                      'private $g = array(\'H\' => 2, \'J\' => 2, \'R\' => 2, \'N\' => 2, \'L\' => 2, \'P\' => 2, \'Z\' => 2)', 
                      'private $c = array( )', 
                      'private $e = 1', 
                      'private $d = array(\'AB\')');

?>