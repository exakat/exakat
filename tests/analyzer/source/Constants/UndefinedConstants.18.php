<?php

trait t {
    function bar() {}
}

trait t2 {
    function bar2() {}
}
trait t3 {
    function bar() {}
}

trait t4 {}

class x {
    use t4 { t::BAR as foo;
            T3::Bar insteadof t;
            }
    use t;
    use t2;
    use t3;
}

echo t3;
echo bar;
?>