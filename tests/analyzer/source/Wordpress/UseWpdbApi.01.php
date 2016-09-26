<?php

$a->query( $table, $data, $format );

$wpdb->insert( $table, $data, $format );
$wpdb->query('INSERT INTO '.$table.' values (1,2,3)');
$wpdb->prepare("INSERT INTO $table values (1,2,4)");
$wpdb->prepare('INSERT INTO table values (1,2,5)');

$wpdb->replace( $table, $data, $format ); 
$wpdb->query('replace INTO '.$table.' values (1,2,3)');
$wpdb->prepare("REPLACE INTO $table values (1,2,4)");
$wpdb->prepare('REPLACE INTO table values (1,2,5)');

$wpdb->update( $table, $data, $where, $format = null, $where_format = null ); 
$wpdb->query('update '.$table.' SET col = 1 WHERE id='.$id);
$wpdb->query("UPDATE $table SET col = 1 WHERE id=$id");
$wpdb->query('UPDATE table SET col = 1 WHERE id=3');

$wpdb->delete( $table, $where, $where_format = null ); 
$wpdb->query('DELETE FROM '.$table.' WHERE id='.$id.' LIMIT 1');
$wpdb->query("DELETE FROM $table WHERE id= $id  LIMIT 1");
$wpdb->query("DELETE FROM table WHERE id= 1  LIMIT 1");


?>