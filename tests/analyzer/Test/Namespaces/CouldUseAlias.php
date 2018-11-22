<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldUseAlias extends Analyzer {
    /* 5 methods */

    public function testNamespaces_CouldUseAlias01()  { $this->generic_test('Namespaces/CouldUseAlias.01'); }
    public function testNamespaces_CouldUseAlias02()  { $this->generic_test('Namespaces/CouldUseAlias.02'); }
    public function testNamespaces_CouldUseAlias03()  { $this->generic_test('Namespaces/CouldUseAlias.03'); }
    public function testNamespaces_CouldUseAlias04()  { $this->generic_test('Namespaces/CouldUseAlias.04'); }
    public function testNamespaces_CouldUseAlias05()  { $this->generic_test('Namespaces/CouldUseAlias.05'); }
}
?>