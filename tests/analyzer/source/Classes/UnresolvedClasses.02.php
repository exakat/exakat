<?php

namespace a\b {
    class abx {        function __construct() { print __METHOD__."\n";} }
}

namespace a2\b2 {
    class abx {        function __construct() { print __METHOD__."\n";} }
}

namespace a {
    class b {        function __construct() { print __METHOD__."\n";} }
}

namespace a2 {
    class b2 {        function __construct() { print __METHOD__."\n";} }
}

namespace b\c {
    class ba {        function __construct() { print __METHOD__."\n";} }
}

namespace {
    class g {        function __construct() { print __METHOD__."\n";} }
    class g2 {        function __construct() { print __METHOD__."\n";} }

    class a {        function __construct() { print __METHOD__."\n";} }
    class a2 {        function __construct() { print __METHOD__."\n";} }
}

namespace d {
    use a\b as e;  // import an alias for a class as a full replacement for a class 
    use a2\b2 as e2;  // import an alias for a class as a full replacement for a class 

    use a; // import a namespace as an authorized prefix
    use a2; // import a namespace as an authorized prefix

    use g; // import a namespace as an authorized prefix
    use g2; // import a namespace as an authorized prefix
    use g as h; // import a namespace as an authorized prefix
    use g2 as h2; // import a namespace as an authorized prefix

    NEW G();
    NEW H();
    NEW G2();
    NEW H2();

    NEW A();
    NEW A2();

    NEW E();
    NEW E2();
    NEW E\ABX(); // MUST BE FOUND (  ALIAS, NO IMPORTED NSNAME, NO LOCAL DEFINITION)
    NEW A\B\ABX(); // MUST BE FOUND (A\AB (NO ALIAS,   IMPORTED NSNAME, NO LOCAL DEFINITION)
    NEW A2\B2\ABX(); // MUST BE FOUND (A\AB (NO ALIAS,   IMPORTED NSNAME, NO LOCAL DEFINITION)
    NEW E2\ABX(); // MUST BE FOUND (  ALIAS, NO IMPORTED NSNAME, NO LOCAL DEFINITION)

    NEW F\ABX(); // MUST NOT BE FOUND (D\F\AB (NO ALIAS, NO IMPORTED NSNAME, NO LOCAL DEFINITION)
    NEW BAX();    // WON'T BE FOUND (LIES IN B)
}



// mere identifier (in use and in call)
// test with Nsnames, 
// nsname with order of -1 ? 
// add support absolue nsnames
// check for case 

// single namespace
// multiple namespaces
// separated namespaces ? 
// homogenize uses (as + alias) (They should depends on namespace tag, and no in block/element

?>