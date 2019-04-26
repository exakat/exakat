<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UndefinedInsteadof extends Analyzer {
    /* 2 methods */

    public function testTraits_UndefinedInsteadof01()  { $this->generic_test('Traits/UndefinedInsteadof.01'); }
    public function testTraits_UndefinedInsteadof02()  { $this->generic_test('Traits/UndefinedInsteadof.02'); }
}
?>