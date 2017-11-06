<?php

$expected     = array('$wpdb->prepare(" WHERE foo = %s", $_GET[\'data\'])',
                      '$wpdb->prepare("SELECT * FROM table2 %s LIMIT %d, %d", 1, 2, $_POST[\'data\'][\'df\'])',
                      '$wpdb->prepare("SELECT * FROM table3 %s LIMIT %d, %d", 1, 2, esc_sql($_POST[\'data\']))',
                     );

$expected_not = array('$wpdb->prepare("SELECT * FROM table4 %s LIMIT %d, %d", 1, 2, 3)',
                     );

?>