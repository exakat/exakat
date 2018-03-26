<?php
$tt = new TokyoTyrant("localhost");
$tt->put("key", "value");
echo $tt->get("key");

$tt2 = new TokyoGodzilla("localhost");
?>