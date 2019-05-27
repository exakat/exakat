<?php

pg_query($res, "select $a from table ");
pg_query($res, "select a from table ");

pg_query($res, "show create table $a");
pg_query($res, "show create table a");

?>