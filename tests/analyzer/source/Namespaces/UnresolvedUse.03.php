<?php

namespace a\b {
    class c {}
    interface i {}
    trait t {}
}

namespace f {
    use a\b\c;
    use a\b\i;
    use a\b\t;
    use a\b;

    use a\b\c2;
    use a\b\i2;
    use a\b\t2;
    use a\b2;
}
?>