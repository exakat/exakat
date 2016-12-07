<?php

//OK, static strings
$wpdb->prepare("insert into a values (1,2,3)");
$wpdb->prepare("insert into a values (1,2,%i)", $a);
$wpdb->prepare(<<<'SQL'
insert into {$wpdb->prefix}$table values (1,2,6)
SQL
);

//OK, using $wpdb only
$wpdb->prepare("insert into {$wpdb->prefix}table values (1,2,4)");
$wpdb->prepare("insert into ".$wpdb->prefix."table values (1,2,5)");
$wpdb->prepare("insert into ".$wpdb->prefix."table values (1,2,%s)", $d);

// KO : using other variables
$wpdb->prepare("insert into ".$wpdb->prefix."$table values (1,2,6)");
$wpdb->prepare("insert into ".$wpdb->prefix.$table." values (1,2,6)");
$wpdb->prepare("insert into {$wpdb->prefix}$table values (1,2,7)");
$wpdb->prepare("insert into $table values (1,2,8)");

$wpdb->prepare(<<<SQL
insert into {$wpdb->prefix}$table values (1,2,6)
SQL
);

$wpdb->prepare(<<<SQL
insert into {$wpdb->prefix}table values (1,2,69)
SQL
);

$wpdb->prepare(<<<SQL
insert into {$x->prefix}table values (1,2,70)
SQL
);

$wpdb->prepare(<<<SQL
insert into $table values (1,2,71)
SQL
);

// Canary
prepare("insert into {$wpdb->prefix}$table values (1,2,7)");

?>