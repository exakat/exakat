<?php

namespace Crypto {

$x = new Hash(); // it is a ext/crypto class when in Crypto namespace
$x = new \Hash(); // it is a \crypto class 

}

namespace {
    $x = new Crypto(); // it is a ext/crypto class when in Crypto namespace
    
    $b = new \crypto\hmac();
    
    // Called like a function
    $c = \crypto\base64(); 
}

?>