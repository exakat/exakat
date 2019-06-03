<?php

class x1 {
    use t {
        t2::a as b;
    }
}

class x2 {
    use t, t2 {
        t::a as a;
        t2::a as b;
    }
}

class x3 {
    use t {
        t::a insteadof c;
    }
}

class x4 {
    use t {
        c::a insteadof t;
    }
}

?>