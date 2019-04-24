<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class ShouldMakeAlias extends Analyzer {
    /* 8 methods */

    public function testNamespaces_ShouldMakeAlias01()  { $this->generic_test('Namespaces/ShouldMakeAlias.01'); }
    public function testNamespaces_ShouldMakeAlias02()  { $this->generic_test('Namespaces/ShouldMakeAlias.02'); }
    public function testNamespaces_ShouldMakeAlias03()  { $this->generic_test('Namespaces/ShouldMakeAlias.03'); }
    public function testNamespaces_ShouldMakeAlias04()  { $this->generic_test('Namespaces/ShouldMakeAlias.04'); }
    public function testNamespaces_ShouldMakeAlias05()  { $this->generic_test('Namespaces/ShouldMakeAlias.05'); }
    public function testNamespaces_ShouldMakeAlias06()  { $this->generic_test('Namespaces/ShouldMakeAlias.06'); }
    public function testNamespaces_ShouldMakeAlias07()  { $this->generic_test('Namespaces/ShouldMakeAlias.07'); }
    public function testNamespaces_ShouldMakeAlias08()  { $this->generic_test('Namespaces/ShouldMakeAlias.08'); }
}
?>