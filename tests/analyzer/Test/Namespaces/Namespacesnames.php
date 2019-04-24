<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class Namespacesnames extends Analyzer {
    /* 7 methods */

    public function testNamespaces_Namespacesnames01()  { $this->generic_test('Namespaces_Namespacesnames.01'); }
    public function testNamespaces_Namespacesnames02()  { $this->generic_test('Namespaces_Namespacesnames.02'); }
    public function testNamespaces_Namespacesnames03()  { $this->generic_test('Namespaces_Namespacesnames.03'); }
    public function testNamespaces_Namespacesnames04()  { $this->generic_test('Namespaces_Namespacesnames.04'); }
    public function testNamespaces_Namespacesnames05()  { $this->generic_test('Namespaces_Namespacesnames.05'); }
    public function testNamespaces_Namespacesnames06()  { $this->generic_test('Namespaces_Namespacesnames.06'); }
    public function testNamespaces_Namespacesnames07()  { $this->generic_test('Namespaces_Namespacesnames.07'); }
}
?>