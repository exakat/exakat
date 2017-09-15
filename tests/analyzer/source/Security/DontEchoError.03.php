<?php

try {
    
} catch (Exception $e) {
    echo $e->getMessage();
    print $e->getMessage();
    echo $e->getTraceAsString();
    echo $e;

    strtolower($e->getMessage());
    print $e->getMassage();
    strtolower($e->getTraceAsString(3));
    strtoupper($e);
    strtoupper($a);
}
?>