<?php

function foo() {
    $where = $wpdb->prepare('where user = %s', $s); 
    $res = $wpdb->prepare(" select * from table $where");
    
    $where2 = $wpdb->prepare('where user = %s', $s); 
    $res = $wpdb->prepare(" select * from table %s", $where2);
    
    $res = $wpdb->prepare(" select * from table where user = %s", $s);
}

?>