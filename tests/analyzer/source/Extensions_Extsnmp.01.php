<?php
$a = snmpwalk("127.0.0.1", "public", ""); 

foreach ($a as $val) {
    echo "$val\n";
}

?>