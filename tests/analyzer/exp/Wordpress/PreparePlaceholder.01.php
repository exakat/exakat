<?php

$expected     = array('$wpdb->prepare("SELECT * FROM table $where LIMIT %c, %F, %s", 1, 2, "5")',
                      '$wpdb->prepare("SELECT * FROM table $where LIMIT %s, %F, %1$s", 1, 2, "5")',
                     );

$expected_not = array('$wpdb->prepare("SELECT * FROM table $where LIMIT %d, %F, %s", 1, 2, "3")',
                      '$wpdb->prepare("SELECT * FROM table $where LIMIT %%s")',
                      '$wpdb->prepare("SELECT * FROM table $where LIMIT %%e")',
                     );

?>