<?php


namespace {
    use a\b\c as UndefinedAlias;
    use a\b\d as DefinedAlias;
    class DefinedClass {}

    new UndefinedClass();
    new DefinedClass(); 
    new UndefinedAlias();
    new DefinedAlias(); 
    new NonexistantAlias(); 

}

namespace a\b {
    class D {}
}

?>