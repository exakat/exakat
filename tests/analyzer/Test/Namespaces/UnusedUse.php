<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once './Test/Analyzer.php';

class UnusedUse extends Analyzer {
    /* 15 methods */

    public function testNamespaces_UnusedUse01()  { $this->generic_test('Namespaces_UnusedUse.01'); }
    public function testNamespaces_UnusedUse02()  { $this->generic_test('Namespaces_UnusedUse.02'); }
    public function testNamespaces_UnusedUse03()  { $this->generic_test('Namespaces_UnusedUse.03'); }
    public function testNamespaces_UnusedUse04()  { $this->generic_test('Namespaces_UnusedUse.04'); }
    public function testNamespaces_UnusedUse05()  { $this->generic_test('Namespaces_UnusedUse.05'); }
    public function testNamespaces_UnusedUse06()  { $this->generic_test('Namespaces_UnusedUse.06'); }
    public function testNamespaces_UnusedUse07()  { $this->generic_test('Namespaces_UnusedUse.07'); }
    public function testNamespaces_UnusedUse08()  { $this->generic_test('Namespaces_UnusedUse.08'); }
    public function testNamespaces_UnusedUse09()  { $this->generic_test('Namespaces_UnusedUse.09'); }
    public function testNamespaces_UnusedUse10()  { $this->generic_test('Namespaces/UnusedUse.10'); }
    public function testNamespaces_UnusedUse11()  { $this->generic_test('Namespaces/UnusedUse.11'); }
    public function testNamespaces_UnusedUse12()  { $this->generic_test('Namespaces/UnusedUse.12'); }
    public function testNamespaces_UnusedUse13()  { $this->generic_test('Namespaces/UnusedUse.13'); }
    public function testNamespaces_UnusedUse14()  { $this->generic_test('Namespaces/UnusedUse.14'); }
    public function testNamespaces_UnusedUse15()  { $this->generic_test('Namespaces/UnusedUse.15'); }
}
?>