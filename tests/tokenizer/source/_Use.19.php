<?php
namespace Name\Space {
    const FOO = 42;
    function f() { echo __FUNCTION__."\n"; }
}

namespace {
    use const Name\Space\c;
    use function Name\Space\f;

    use const Name\Space\FOO2,  Name\Space\FOO3;
    use function Name\Space\FOO2,  Name\Space\FOO3;

    use const Name\Space\FOO as a1;
    use const Name\Space\FOO2 as a2,  Name\Space\FOO3 as a3;
    use const Name\Space\FOO2 as a4,  Name as a5, Name\Space\FOO3 as a6;

    echo FOO."\n";
    f2();
}
?>