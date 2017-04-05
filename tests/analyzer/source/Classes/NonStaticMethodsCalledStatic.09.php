<?php

namespace T\U\V ;
class x {
    public function abc() { print __METHOD__."\n"; }
    static function sabc() { print __METHOD__."\n"; }
}


$a = new x();

$a->abc();   // OK
$a->sabc();  // Possible but weird

\T\U\V\x::abc();    // Should not be possible but actually is
\T\U\V\x::sabc();   // OK

?>