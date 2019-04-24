<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class EmptyNamespace extends Analyzer {
    /* 5 methods */

    public function testNamespaces_EmptyNamespace01()  { $this->generic_test('Namespaces_EmptyNamespace.01'); }
    public function testNamespaces_EmptyNamespace02()  { $this->generic_test('Namespaces_EmptyNamespace.02'); }
    public function testNamespaces_EmptyNamespace03()  { $this->generic_test('Namespaces_EmptyNamespace.03'); }
    public function testNamespaces_EmptyNamespace04()  { $this->generic_test('Namespaces/EmptyNamespace.04'); }
    public function testNamespaces_EmptyNamespace05()  { $this->generic_test('Namespaces/EmptyNamespace.05'); }
}
?>