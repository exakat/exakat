<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NoMagicWithArray extends Analyzer {
    /* 4 methods */

    public function testClasses_NoMagicWithArray01()  { $this->generic_test('Classes/NoMagicWithArray.01'); }
    public function testClasses_NoMagicWithArray02()  { $this->generic_test('Classes/NoMagicWithArray.02'); }
    public function testClasses_NoMagicWithArray03()  { $this->generic_test('Classes/NoMagicWithArray.03'); }
    public function testClasses_NoMagicWithArray04()  { $this->generic_test('Classes/NoMagicWithArray.04'); }
}
?>