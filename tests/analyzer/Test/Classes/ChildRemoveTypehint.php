<?php

namespace Test\Classes;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ChildRemoveTypehint extends Analyzer {
    /* 3 methods */

    public function testClasses_ChildRemoveTypehint01()  { $this->generic_test('Classes/ChildRemoveTypehint.01'); }
    public function testClasses_ChildRemoveTypehint02()  { $this->generic_test('Classes/ChildRemoveTypehint.02'); }
    public function testClasses_ChildRemoveTypehint03()  { $this->generic_test('Classes/ChildRemoveTypehint.03'); }
}
?>