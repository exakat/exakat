<?php
$object->query("select $a from table ");
$object2->query('select '.$a.' from table ');

$object2->notQuery($res,  'select '.$a.' from table ');
$wrongPosition->query(2, 'select '.$a.' from table ');

?>