<?php

$expected     = array('sqlsrv_query($res, "select " . $a . " from table ")',
                      '\\cubrid_query($res, \'select \' . $a . \' from table \')',
                      'pg_query($res, "select $a from table ")',
                      '\\ingres_query($res, <<<\'SQL\'
select * from table $table;
SQL)',
                     );

$expected_not = array('pg_query($res, \'select a from table \')',
                     );

?>