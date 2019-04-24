<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class HiddenUse extends Analyzer {
    /* 8 methods */

    public function testNamespaces_HiddenUse01()  { $this->generic_test('Namespaces/HiddenUse.01'); }
    public function testNamespaces_HiddenUse02()  { $this->generic_test('Namespaces/HiddenUse.02'); }
    public function testNamespaces_HiddenUse03()  { $this->generic_test('Namespaces/HiddenUse.03'); }
    public function testNamespaces_HiddenUse04()  { $this->generic_test('Namespaces/HiddenUse.04'); }
    public function testNamespaces_HiddenUse05()  { $this->generic_test('Namespaces/HiddenUse.05'); }
    public function testNamespaces_HiddenUse06()  { $this->generic_test('Namespaces/HiddenUse.06'); }
    public function testNamespaces_HiddenUse07()  { $this->generic_test('Namespaces/HiddenUse.07'); }
    public function testNamespaces_HiddenUse08()  { $this->generic_test('Namespaces/HiddenUse.08'); }
}
?>