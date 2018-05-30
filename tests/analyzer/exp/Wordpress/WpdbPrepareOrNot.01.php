<?php

$expected     = array('$wpdb->prepare("insert into " . $wpdb->prefix . "$table values (1,2,6)")',
                      '$wpdb->prepare(<<<SQL
insert into {$wpdb->prefix}$table values (1,2,6)
SQL)',
                      '$wpdb->prepare(<<<SQL
insert into {$x->prefix}table values (1,2,70)
SQL)',
                      '$wpdb->prepare(<<<SQL
insert into $table values (1,2,71)
SQL)',
                      '$wpdb->prepare("insert into {$wpdb->prefix}table values (1,2,4)")',
                      '$wpdb->prepare("insert into a values (1,2,3)")',
                     );

$expected_not = array('prepare("insert into {$wpdb->prefix}$table values (1,2,7)")',
                      '$wpdb->prepare("insert into a values (1,2,3)")',
                      '$wpdb->prepare(<<<\'SQL\'
insert into {$wpdb->prefix}$table values (1,2,6)
SQL);',
                      '$wpdb->prepare("insert into a values (1,2,%i)", $a)',
                      '$wpdb->prepare("insert into ".$wpdb->prefix."table values (1,2,%s)", $d)',
                     );

?>