<?php 
    $a = 'b';
    $c = 1;
    switch($a) : 
    default  ?>A<?php 
        ++$c; 
    ?>C<?php 
    break 1;
    case "d" ?>B<?php 
        ++$c; 
    ?>B<?php 
    endswitch; 
    
    print $c;
?>