<?php

// All valid %
$query = $wpdb->prepare("SELECT * FROM table $where LIMIT %d, %F, %s", 1, 2, "3");

// All invalid %
$query = $wpdb->prepare("SELECT * FROM table $where LIMIT %c, %F, %s", 1, 2, "5");
$query = $wpdb->prepare("SELECT * FROM table $where LIMIT %s, %F, %1$s", 1, 2, "5");

// All escaped %
$query = $wpdb->prepare("SELECT * FROM table $where LIMIT %%s");
$query = $wpdb->prepare("SELECT * FROM table $where LIMIT %%e");

?>