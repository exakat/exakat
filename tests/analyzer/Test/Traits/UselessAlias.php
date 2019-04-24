<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UselessAlias extends Analyzer {
    /* 2 methods */

    public function testTraits_UselessAlias01()  { $this->generic_test('Traits/UselessAlias.01'); }
    public function testTraits_UselessAlias02()  { $this->generic_test('Traits/UselessAlias.02'); }
}
?>