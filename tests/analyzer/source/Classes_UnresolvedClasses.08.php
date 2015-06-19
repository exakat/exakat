<?php

namespace a {
    class b {        function __construct() { print __METHOD__."\n";} }

    new b();
}

namespace c\d {
    class e {        function __construct() { print __METHOD__."\n";} }

    new e();
}

?>