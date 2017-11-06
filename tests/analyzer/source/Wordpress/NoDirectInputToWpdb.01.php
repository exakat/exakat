<?php

$where = $wpdb->prepare(" WHERE foo = %s", $_GET['data']);
$query = $wpdb->prepare("SELECT * FROM table $where LIMIT %d, %d", 1, 2);

$query = $wpdb->prepare("SELECT * FROM table2 %s LIMIT %d, %d", 1, 2, $_POST['data']['df']);

$where = "WHERE foo = '" .  . "'";
$query = $wpdb->prepare("SELECT * FROM table3 %s LIMIT %d, %d", 1, 2, esc_sql($_POST['data']));

$query = $wpdb->prepare("SELECT * FROM table4 %s LIMIT %d, %d", 1, 2, 3);

?>