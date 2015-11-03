<?php


namespace {
    use a\b\c as UndefinedAlias;
    use a\b\d as DefinedAlias;
    class DefinedClass {}

    DefinedClass::y();
    DefinedAlias::y();

    UndefinedClass::y();
    UndefinedAlias::y();
    NonexistantAlias::y();
}

namespace a\b {
    class D {}
}

?>