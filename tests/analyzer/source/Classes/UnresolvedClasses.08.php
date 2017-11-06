<?php

namespace a {
    class b {        function __construct() { print __METHOD__."\n";} }

    new b();
}

namespace c\d {
    use f;
    
    class e {        function __construct() { print __METHOD__."\n";} }

    new e();
    new f();
}

?>