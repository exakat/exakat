<?php


function a(&$b = array(1,2 => array(3,2),3), &$c=1, &$d) {
    print_r($b);
}

?>