<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class Alias extends Analyzer {
    /* 1 methods */

    public function testNamespaces_Alias01()  { $this->generic_test('Namespaces_Alias.01'); }
}
?>