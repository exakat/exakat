<?php

try {
    $a++;
    throw new Exception();
} catch (myException $e) {

} catch (Exception $e) {

}

$b->throw('method');
?>