<?php

namespace a\b {
    class ab {        function __construct() { print __method__."\n";} }
}

namespace a2\b2 {
    class ab {        function __construct() { print __method__."\n";} }
}

namespace a {
    class b {        function __construct() { print __method__."\n";} }
}

namespace a2 {
    class b2 {        function __construct() { print __method__."\n";} }
}

namespace b\c {
    class ba {        function __construct() { print __method__."\n";} }
}

namespace {
    class g {        function __construct() { print __method__."\n";} }
    class g2 {        function __construct() { print __method__."\n";} }

    class a {        function __construct() { print __method__."\n";} }
    class a2 {        function __construct() { print __method__."\n";} }
}

namespace d {
    use a\b as e, a2\b2 as e2;  // import an alias for a class as a full replacement for a class 

    use a, a2; // import a namespace as an authorized prefix

    use g, g2; // import a namespace as an authorized prefix
    use g as h, g2 as h2; // import a namespace as an authorized prefix

    new g();
    new h();
    new g2();
    new h2();

    new a();
    new a2();

    new e();
    new e2();
    new e\ab(); // must be found (  alias, no imported nsname, no local definition)
    new a\b\ab(); // must be found (a\ab (no alias,   imported nsname, no local definition)
    new a2\b2\ab(); // must be found (a\ab (no alias,   imported nsname, no local definition)
    new e2\ab(); // must be found (  alias, no imported nsname, no local definition)

    new f\ab(); // must not be found (d\f\ab (no alias, no imported nsname, no local definition)
    new ba(); // won't be found (lies in b)
}


?>