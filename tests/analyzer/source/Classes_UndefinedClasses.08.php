<?php


namespace {
    use a\b\c as UndefinedAlias;
    use a\b\d as DefinedAlias;
    class DefinedClass {}

    UndefinedClass::y();
    DefinedClass::y();
    UndefinedAlias::y();
    DefinedAlias::y();
    NonexistantAlias::y();

}

namespace a\b {
    class D {}
}

?>