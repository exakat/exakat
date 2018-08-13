<?php
    $xml = new XMLReader();
    if(!$xml->open($file)){
        die("Failed to open input file.");
    }
    $n=0;
    $x=0;
    while($xml->read()){
    
    }
    
    echo xml_read();
?>