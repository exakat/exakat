<?php

class x {
    function __construct() {}
}
class x {
    function __construct($x) { $x = 2;}
}
class x {
    function __construct($x = null) { $x = 2;}
}
class x {
    function __construct($x = 1) { $x = 2;}
}
class x {
    function __construct($x) { $x = new X;}
}
class x {
    function __construct($x) { $x = foo();}
}
class x {
    function __construct($x, $y, $z) { $x = $y;
                                $z = null;
                                $y = CONSTANTE;
                                }
}
class x {
    function __construct($x) { $x = CONSTANTE; }
}
class x {
    function __construct($x) { $x = 1 + 3; }
}

?>