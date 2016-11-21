<?php

$anArray = range(1, 10);

if (array_search(1, $anArray) !== false) {
    print "Found";
} else {
    print "Not Found";
}

if (array_key_exists(1, $anArray) !== false) {
    print "Found";
} else {
    print "Not Found";
}

if (isset($anArray[1]) !== false) {
    print "Found";
} else {
    print "Not Found";
}

?>