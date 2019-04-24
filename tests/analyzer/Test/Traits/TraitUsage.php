<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class TraitUsage extends Analyzer {
    /* 3 methods */

    public function testTraits_TraitUsage01()  { $this->generic_test('Traits_TraitUsage.01'); }
    public function testTraits_TraitUsage02()  { $this->generic_test('Traits/TraitUsage.02'); }
    public function testTraits_TraitUsage03()  { $this->generic_test('Traits/TraitUsage.03'); }
}
?>