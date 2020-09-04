<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UsedPrivateMethod extends Analyzer {
    /* 5 methods */

    public function testClasses_UsedPrivateMethod01()  { $this->generic_test('Classes_UsedPrivateMethod.01'); }
    public function testClasses_UsedPrivateMethod02()  { $this->generic_test('Classes_UsedPrivateMethod.02'); }
    public function testClasses_UsedPrivateMethod03()  { $this->generic_test('Classes/UsedPrivateMethod.03'); }
    public function testClasses_UsedPrivateMethod04()  { $this->generic_test('Classes/UsedPrivateMethod.04'); }
    public function testClasses_UsedPrivateMethod05()  { $this->generic_test('Classes/UsedPrivateMethod.05'); }
}
?>