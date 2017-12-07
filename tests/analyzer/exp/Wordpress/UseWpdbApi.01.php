<?php

$expected     = array('$wpdb->query(\'DELETE FROM \' . $table . \' WHERE id=\' . $id . \' LIMIT 1\')',
                      '$wpdb->query(\'replace INTO \' . $table . \' values (1,2,3)\')',
                      '$wpdb->query(\'update \' . $table . \' SET col = 1 WHERE id=\' . $id)',
                      '$wpdb->query(\'INSERT INTO \' . $table . \' values (1,2,3)\')',
                      '$wpdb->query("DELETE FROM $table WHERE id= $id  LIMIT 1")',
                      '$wpdb->prepare("REPLACE INTO $table values (1,2,4)")',
                      '$wpdb->query("UPDATE $table SET col = 1 WHERE id=$id")',
                      '$wpdb->prepare("INSERT INTO $table values (1,2,4)")',
                      '$wpdb->query("DELETE FROM table WHERE id= 1  LIMIT 1")',
                      '$wpdb->prepare(\'REPLACE INTO table values (1,2,5)\')',
                      '$wpdb->query(\'UPDATE table SET col = 1 WHERE id=3\')',
                      '$wpdb->prepare(\'INSERT INTO table values (1,2,5)\')',
                     );

$expected_not = array('$a->query( $table, $data, $format )',
                      '$wpdb->insert( $table, $data, $format )',
                      '$wpdb->replace( $table, $data, $format )',
                      '$wpdb->query("UPDATE $table SET col = 1 WHERE id=$id")',
                     );

?>