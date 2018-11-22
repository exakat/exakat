<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class GlobalImport extends Analyzer {
    /* 3 methods */

    public function testNamespaces_GlobalImport01()  { $this->generic_test('Namespaces/GlobalImport.01'); }
    public function testNamespaces_GlobalImport02()  { $this->generic_test('Namespaces/GlobalImport.02'); }
    public function testNamespaces_GlobalImport03()  { $this->generic_test('Namespaces/GlobalImport.03'); }
}
?>