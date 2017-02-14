<?php

namespace c {
    use a\b; 

    $c = new b\d(); 
    $c = new b\e(); 

    $d = new b(); 

    $e = new b\d; 
    $e = new b\e; 
    
    var_dump($c instanceof b\d);
    var_dump($d instanceof b);
}

namespace a\b {
    class d {}
}

namespace a {
    class b {}
}

?>