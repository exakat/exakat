<?php

$expected     = array('count($sql, true)',
                     );

$expected_not = array('dirname(__FILE__)',
                      'odbc_autocommit($this->conn_id, FALSE)',
                      'session_set_save_handler($class, TRUE)',
                      'substr(__FILE__, 0, 5)',
                      'echo 0, 1.1, false, CONSTANTE, ns\\Name',
                      'print <<<HHH

HHH',
                      'sprintf(<<<HHHH

HHHH
,$a)',
                     );

?>