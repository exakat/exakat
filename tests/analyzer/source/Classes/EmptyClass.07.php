<?php
    class emptyClass {}
    class alsoEmptyClass { public $p = 1; }
    class alsoEmptyClass2 { const C = 1; }
    class nonEmptyClass { public function foo() {} }

    class alsoEmptyDerivedClass2 extends B { }
    class alsoEmptyDerivedClass extends B { public $p = 1; }
    class nonEmptyDerivedClass3 extends B { public function foo2() {} }
    class nonEmptyDerivedClass4 extends B { public const C = 1; }
?>