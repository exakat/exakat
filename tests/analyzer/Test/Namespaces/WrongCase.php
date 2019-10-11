<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class WrongCase extends Analyzer {
    /* 1 methods */

    public function testNamespaces_WrongCase01()  { $this->generic_test('Namespaces/WrongCase.01'); }
}
?>