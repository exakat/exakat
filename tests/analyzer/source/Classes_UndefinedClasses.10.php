<?php

namespace c {
    use a\b; 
    $c = new b\d(); 
    $d = new b(); 
    
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