<?php

trait t {
    function h() {}
}

class x {
    use t { t::f as f; }
}

class x2 {
    use t { f as f; }
}

class x3 {
    use t { f as F; }
}

class x4 {
    use t { F as f; }
}

class x5 {
    use t { h as g; }
}

?>