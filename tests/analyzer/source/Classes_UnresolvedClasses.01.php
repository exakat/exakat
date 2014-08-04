<?php

namespace a {
    class ab {        function __construct() { print __METHOD__."\n";} }
    
    new ba(); // won't be found (lies in b)
    new ab(); // must be found
}

namespace b {
    class ba {        function __construct() { print __METHOD__."\n";} }
}

namespace c {
    use a;

    new ab(); // must be found
    new ba(); // won't be found (lies in b)
}

namespace d12 {
    use a as e;  // import an alias for a class as a full replacement for a class 
    use a; // import a namespace as an authorized prefix

    new e\ab(); // must be found (  alias, no imported nsname, no local definition)
    new a\ab(); // must be found (a\ab (no alias,   imported nsname, no local definition)
    new f\ab(); // must not be found (d\f\ab (no alias, no imported nsname, no local definition)
    new ba(); // won't be found (lies in b)
}

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

?>