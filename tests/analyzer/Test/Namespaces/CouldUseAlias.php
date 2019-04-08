<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class CouldUseAlias extends Analyzer {
    /* 8 methods */

    public function testNamespaces_CouldUseAlias01()  { $this->generic_test('Namespaces/CouldUseAlias.01'); }
    public function testNamespaces_CouldUseAlias02()  { $this->generic_test('Namespaces/CouldUseAlias.02'); }
    public function testNamespaces_CouldUseAlias03()  { $this->generic_test('Namespaces/CouldUseAlias.03'); }
    public function testNamespaces_CouldUseAlias04()  { $this->generic_test('Namespaces/CouldUseAlias.04'); }
    public function testNamespaces_CouldUseAlias05()  { $this->generic_test('Namespaces/CouldUseAlias.05'); }
    public function testNamespaces_CouldUseAlias06()  { $this->generic_test('Namespaces/CouldUseAlias.06'); }
    public function testNamespaces_CouldUseAlias07()  { $this->generic_test('Namespaces/CouldUseAlias.07'); }
    public function testNamespaces_CouldUseAlias08()  { $this->generic_test('Namespaces/CouldUseAlias.08'); }
}
?>