<?php

namespace a {
    class b {        function __construct() { print __method__."\n";} }
}

namespace a\b {
    class c {        function __construct() { print __method__."\n";} }
}

namespace a\b\c {
    class d {        function __construct() { print __method__."\n";} }
}

namespace a\b\c\d {
    class e {        function __construct() { print __method__."\n";} }
    class f {        function __construct() { print __method__."\n";} }
}

namespace d {
    use a\b;
    use a\b\c;
    use a\b\c\d;
    use a\b\c\d\e, a\b\c\d\f;
    
    new b();
    new c();
    new d();
    new e();
    new f();
    new g();
}



// mere identifier (in use and in call)
// test with nsnames, 
// nsname with order of -1 ? 
// add support absolue nsnames
// check for case 
// multiple namespaces
// single namespace 


// single namespace
// separated namespaces ? 
// homogenize uses (as + alias) (they should depends on namespace tag, and no in block/element

?>