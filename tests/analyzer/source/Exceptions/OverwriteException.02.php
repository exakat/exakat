<?php

try {
    doSomething();
} catch (SomeException $a) { 
    // $e is overwritten 
    $a = new anotherException($a->getMessage()); 
    throw $a;
} catch (SomeOtherException $b) { 
    // $b is chained with the next exception 
    $b = new Exception($e->getMessage(), 0, $b); 
    throw $b;
}

?>