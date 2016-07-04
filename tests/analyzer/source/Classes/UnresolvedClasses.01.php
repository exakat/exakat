<?php

namespace a {
    class abx {        function __construct() { print __METHOD__."\n";} }
    
    new bax(); // won't be found (lies in b)
    new abx(); // must be found
}

namespace b {
    class bax {        function __construct() { print __METHOD__."\n";} }

    new bax(); // must be found
    new abx(); // won't be found (lies in a)
}

namespace c {
    use a;

    new abx(); // won't be found (lies in a)
    new bax(); // won't be found (lies in b)
}

namespace d12 {
    use a as e;  // import an alias for a class as a full replacement for a class 
    use a; // import a namespace as an authorized prefix

    new e\abx(); // must be found (  alias, no imported nsname, no local definition)
    new a\abx(); // must be found (a\ab (no alias,   imported nsname, no local definition)
    new f\abx(); // must not be found (d\f\ab (no alias, no imported nsname, no local definition)
    new bax(); // won't be found (lies in b)
}
/*
namespace d1 {
    use a as e;  // import an alias for a class as a full replacement for a class 

    new e\ab(); // must be found (  alias, no imported nsname, no local definition)
    new a\ab(); // must be found (a\ab (no alias,   imported nsname, no local definition)
    new f\ab(); // must not be found (d\f\ab (no alias, no imported nsname, no local definition)
    new ba(); // won't be found (lies in b)
}

namespace d2 {
    use a; // import a namespace as an authorized prefix

    new e\ab(); // must be found (  alias, no imported nsname, no local definition)
    new a\ab(); // must be found (a\ab (no alias,   imported nsname, no local definition)
    new f\ab(); // must not be found (d\f\ab (no alias, no imported nsname, no local definition)
    new ba(); // won't be found (lies in b)
}

namespace d {

    new e\ab(); // must be found (  alias, no imported nsname, no local definition)
    new a\ab(); // must be found (a\ab (no alias,   imported nsname, no local definition)
    new f\ab(); // must not be found (d\f\ab (no alias, no imported nsname, no local definition)
    new ba(); // won't be found (lies in b)
}

namespace f {
    class ab {        function __construct() { print __METHOD__."\n";} }
}
*/
?>