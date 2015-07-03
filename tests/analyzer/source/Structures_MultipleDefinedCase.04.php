<?php

$a = 1;
$a = '1';
$variousTypes = true;

switch($variousTypes) {
    case array(13) : 
        print __LINE__."\n";
        var_dump($a);
        break 1;

    case 1 : 
        print __LINE__."\n";
        var_dump($a);
        break 1;

    case '1' : 
        print __LINE__."\n";
        var_dump($a);
        break 1;

    case true : 
        print __LINE__."\n";
        var_dump($a);
        break 1;

    default : 
        print "Default\n";
}

switch($allSameType) {
    case 1 : 
        print __LINE__."\n";
        var_dump($a);
        break 1;

    case 2 : 
        print __LINE__."\n";
        var_dump($a);
        break 1;

    case 0 : 
        print __LINE__."\n";
        var_dump($a);
        break 1;

    default : 
        print "Default\n";
}

