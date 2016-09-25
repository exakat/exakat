<?php

// Not a $wpdb
$someVar->get_var("SELECT * FROM $group_table ORDER BY name");

// No variable in the string. 
$wpdb->get_var('SELECT * FROM $group_table ORDER BY name');

// variable in the string is a wpdb property. 
$wpdb->get_var("SELECT * FROM $wpdb->table_prefix ORDER BY name");

// Command is authorized (can't be made a prepared statement)
$wpdb->prepare("DROP TABLE IF EXISTS $table_prefix");
$wpdb->prepare("SHOW TABLES LIKE $like");

// variable in the string is not a wpdb property. 
$wpdb->prepare("SELECT * FROM $table_prefix ORDER BY name");

// 2 variable in the string
$wpdb->prepare("SELECT * FROM $table_prefix ORDER BY $wpdb->table_prefix");


// variable in the concatenation is a wpdb property. 
$wpdb->get_var('SELECT * FROM '.$wpdb->table_prefix.' ORDER BY name');

// variable in the concatenation is a wpdb property. 
$wpdb->get_row('SELECT * FROM '.$table_prefix.' ORDER BY name');

$wpdb->prepare("SELECT * FROM ".$table_prefix." ORDER BY ".$wpdb->table_prefix);

// Command is authorized (can't be made a prepared statement)
$wpdb->prepare("DROP TABLE IF EXISTS ".$table_prefix);
$wpdb->prepare("SHOW TABLES LIKE ".$like);

?>