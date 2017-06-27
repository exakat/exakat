<?php

echo <<<SQL
SELECT * FROM table1;
SQL;

echo <<<SQL
SELECT * FROM table2;
SQL;

echo <<<SQL
SELECT * FROM table3;
SQL;

echo <<<SQL
SELECT * FROM table4;
SQL;

echo <<<SQL
SELECT * FROM table5;
SQL;

echo <<<SQL
SELECT * FROM table11;
SQL;

echo <<<SQL
SELECT * FROM table12;
SQL;

echo <<<SQL
SELECT * FROM table13;
SQL;

// Nowdoc
echo <<<'SQL'
SELECT * FROM table14;
SQL;

echo <<<SQL
SELECT * FROM table15;
SQL;


echo <<<HEREDOC
SELECT * FROM table215;
HEREDOC;

?>