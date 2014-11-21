<?php


namespace {
    use a\b\c as DefinedAliasUndefinedClass;
    use a\b\d as DefinedAlias;
    class DefinedLocalClass {}

    new DefinedLocalClass(); 
    new DefinedAlias(); 

    new DefinedAliasUndefinedClass();
    new NonexistantAlias(); 
    new UndefinedClass();
}

namespace a\b {
    class D {}
}

?>