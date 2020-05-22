<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UnusedClassTrait extends Analyzer {
    /* 2 methods */

    public function testTraits_UnusedClassTrait01()  { $this->generic_test('Traits/UnusedClassTrait.01'); }
    public function testTraits_UnusedClassTrait02()  { $this->generic_test('Traits/UnusedClassTrait.02'); }
}
?>