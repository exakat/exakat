<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Extreflection extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extreflection01()  { $this->generic_test('Extensions_Extreflection.01'); }
    public function testExtensions_Extreflection02()  { $this->generic_test('Extensions/Extreflection.02'); }
}
?>