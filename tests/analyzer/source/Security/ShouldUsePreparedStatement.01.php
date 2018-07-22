<?php
mysql_query($res, <<<SQL
select $a from table 
SQL
);

pg_query($res, "select $a from table ");
pg_query($res, "select a from table ");

sqlsrv_query($res, "select ".$a." from table ");
\cubrid_query($res, 'select '.$a.' from table ');
//sybase_query
//ingres_query

// OK, as no concatenation
mysqli_query($res, <<<SQL
select * from table 
SQL
);

// Nowdoc
\ingres_query($res, <<<'SQL'
select * from table $table;
SQL
);

?>