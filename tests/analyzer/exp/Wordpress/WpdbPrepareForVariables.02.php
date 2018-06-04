<?php

$expected     = array('$wpdb->get_col(\'SELECT * FROM table WHERE id = "\' . $x1 . \'"\')',
                      '$wpdb->get_results("SELECT * FROM {$wpdb->prefix}table WHERE id = \'$x\'")',
                      '$wpdb->get_row("SELECT * FROM table WHERE id = \'$x2\'")',
                      '$wpdb->get_var(<<<SQL
SELECT * FROM table WHERE id = \'$x3\'
SQL)',
                     );

$expected_not = array('$wpdb->get_results("SELECT * FROM {$wpdb->prefix}table WHERE id = \'$x\'")',
                      '$wpdb->get_results("SELECT * FROM {$wpdb->prefix}table WHERE id = 1")',
                      '$wpdb->prepare("SELECT * FROM {$wpdb->prefix}table2 WHERE id = 1")',
                     );

?>