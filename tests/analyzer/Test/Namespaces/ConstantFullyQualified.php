<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class ConstantFullyQualified extends Analyzer {
    /* 2 methods */

    public function testNamespaces_ConstantFullyQualified01()  { $this->generic_test('Namespaces_ConstantFullyQualified.01'); }
    public function testNamespaces_ConstantFullyQualified02()  { $this->generic_test('Namespaces_ConstantFullyQualified.02'); }
}
?>