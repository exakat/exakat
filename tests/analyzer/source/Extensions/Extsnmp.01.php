<?php
$a = snmpwalk("127.0.0.1", "public", ""); 
$a = snmp_get_quick_print();
$a = snmp_quick_get_print();

foreach ($a as $val) {
    echo "$val\n";
}

?>