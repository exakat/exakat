<?php

$expected     = array('pg_query($res, "select $a from table ")',
                     );

$expected_not = array('pg_query($res, "select a from table ")',
                      'pg_query($res, "show create table a")',
                      'pg_query($res, "show create table $a")',
                     );

?>