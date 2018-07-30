<?php

$expected     = array('sqlsrv_query($res, "select " . $a . " from table ")',
                      '\\cubrid_query($res, \'select \' . $a . \' from table \')',
                      'pg_query($res, "select $a from table ")',
                     );

$expected_not = array('pg_query($res, \'select a from table \')',
                     );

?>