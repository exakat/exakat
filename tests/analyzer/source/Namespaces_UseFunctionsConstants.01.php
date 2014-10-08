<?php 

namespace {
    use function foo\bar as foo_bar;
    use const foo\BAZ as FOO_BAZ;
    use foo3\BAZ as FOO_BAZ;
    use foo2\BAZ as FOO_BAZ;

    var_dump(foo_bar());
    var_dump(FOO_BAZ);
}

?>