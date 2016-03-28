<?php
$tt = new TokyoTyrant("localhost");
$tt->put("key", "value");
echo $tt->get("key");
?>