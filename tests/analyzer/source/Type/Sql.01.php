<?php

// SQL in a string
$query = '   SELECT name FROM users WHERE id = 1';

// SQL in a concatenation
$query = 'SELECT name FROM '.$table_users.' WHERE id = 2';

// SQL in a Heredoc
$query = <<<SQL
SELECT name FROM $table_users WHERE id = 3
SQL;

$query2 = <<<'SQL'
SELECT name FROM $table_users WHERE id = 4
SQL;

$query2 = <<<'SQL'
SELECT name FROM $table_users WHERE id = 5
SQL;

// non-SQL in a string
$query = 'SALECT name FROM users WHERE id = 6';

?>