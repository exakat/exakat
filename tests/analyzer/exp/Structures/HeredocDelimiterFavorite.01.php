<?php

$expected     = array('<<<HEREDOC
SELECT * FROM table215;
HEREDOC
');

$expected_not = array('<<<SQL
SELECT * FROM table15;
SQL',
                      '<<<\'SQL\'
SELECT * FROM table14;
SQL');

?>