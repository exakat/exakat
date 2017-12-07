<?php

$expected     = array('$unused = array(\'H\' => 2)',
                     );

$expected_not = array('$f = AC',
                      '$b = array(\'D\' => array(\'E\' => \'F\'), \'G\' => array(\'H\' => \'I\', \'J\' => \'I\', \'L\' => \'M\', \'N\' => \'O\', \'P\' => \'Q\', \'R\' => \'S\', \'T\' => \'U\', \'V\' => \'W\', \'X\' => \'I\', \'Z\' => \'AA\',  ))',
                      '$unused = array(\'H\' => 2)',
                      '$g = array(\'H\' => 2, \'J\' => 2, \'R\' => 2, \'N\' => 2, \'L\' => 2, \'P\' => 2, \'Z\' => 2)',
                      '$c = array( )',
                      '$e = 1',
                      '$d = array(\'AB\')',
                     );

?>