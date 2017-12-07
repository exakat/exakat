<?php

$expected     = array('"SELECT * FROM " . $table_prefix . " ORDER BY " . $wpdb->table_prefix',
                      '\'SELECT * FROM \' . $wpdb->table_prefix . \' ORDER BY name\'',
                      '"SELECT * FROM $table_prefix ORDER BY name"',
                     );

$expected_not = array('"SELECT * FROM $group_table ORDER BY name"',
                      '"DROP TABLE IF EXISTS $table_prefix"',
                      '\'SELECT * FROM $group_table ORDER BY name\'',
                      '"SELECT * FROM $wpdb->table_prefix ORDER BY name"',
                      '"SHOW TABLES LIKE $like"',
                      '\'SELECT * FROM \' . $wpdb->table_prefix . \' ORDER BY name\'',
                      '"SELECT * FROM " . $table_prefix . " ORDER BY " . $wpdb->table_prefix',
                      '"DROP TABLE IF EXISTS " . $table_prefix',
                      '"SHOW TABLES LIKE ".$like',
                     );

?>