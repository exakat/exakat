<?php
$coll  = collator_create('en_US');
$result = collator_compare($coll, "string#1", "string#2");
$coll  = collator_ksort('en_US');
?>