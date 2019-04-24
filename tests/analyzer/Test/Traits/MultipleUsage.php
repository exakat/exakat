<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleUsage extends Analyzer {
    /* 4 methods */

    public function testTraits_MultipleUsage01()  { $this->generic_test('Traits/MultipleUsage.01'); }
    public function testTraits_MultipleUsage02()  { $this->generic_test('Traits/MultipleUsage.02'); }
    public function testTraits_MultipleUsage03()  { $this->generic_test('Traits/MultipleUsage.03'); }
    public function testTraits_MultipleUsage04()  { $this->generic_test('Traits/MultipleUsage.04'); }
}
?>