<?php


namespace {
    use a\b\c as UndefinedAlias;
    use a\b\d as DefinedAlias;
    class DefinedClass {}

    function a1 (UndefinedClass $x) {} ;
    function a2 (DefinedClass $x) {} ;
    function a3 (UndefinedAlias $x) {} ;
    function a4 (DefinedAlias $x) {} ;
    function a5 (NonexistantAlias $x) {} ;
}

namespace a\b {
    class D {}
}

?>