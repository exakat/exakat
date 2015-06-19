<?php 

namespace {
    use function foo\bar as foo_bar;
    use function foo\bar2 as foo_bar2, foo\bar3 as foo_bar3, foo\bar4 as foo_bar4;
    use const foo\BAZ as FOO_BAZ0;
    use const foo\BAZ1 as FOO_BAZ_CONST1, foo\BAZ2 as FOO_BAZ_CONST2, foo\BAZ3 as FOO_BAZ_CONST3;
    use foo3\BAZ as FOO_BAZ1_CLASS;
    use foo2\BAZ as FOO_BAZ2_CLASS;

    var_dump(foo_bar());
    var_dump(FOO_BAZ);
}

?>