<?php
    $exploded = explode('.', $filename);
    
    if (count($exploded) > 1) {
    	$extension = array_pop($exploded);
    }
    
    $temp = split('a', $config);

?>