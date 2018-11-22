<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnresolvedUse extends Analyzer {
    /* 6 methods */

    public function testNamespaces_UnresolvedUse01()  { $this->generic_test('Namespaces_UnresolvedUse.01'); }
    public function testNamespaces_UnresolvedUse02()  { $this->generic_test('Namespaces_UnresolvedUse.02'); }
    public function testNamespaces_UnresolvedUse03()  { $this->generic_test('Namespaces_UnresolvedUse.03'); }
    public function testNamespaces_UnresolvedUse04()  { $this->generic_test('Namespaces_UnresolvedUse.04'); }
    public function testNamespaces_UnresolvedUse05()  { $this->generic_test('Namespaces_UnresolvedUse.05'); }
    public function testNamespaces_UnresolvedUse06()  { $this->generic_test('Namespaces/UnresolvedUse.06'); }
}
?>