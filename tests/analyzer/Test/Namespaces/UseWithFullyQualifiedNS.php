<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class UseWithFullyQualifiedNS extends Analyzer {
    /* 3 methods */

    public function testNamespaces_UseWithFullyQualifiedNS01()  { $this->generic_test('Namespaces_UseWithFullyQualifiedNS.01'); }
    public function testNamespaces_UseWithFullyQualifiedNS02()  { $this->generic_test('Namespaces/UseWithFullyQualifiedNS.02'); }
    public function testNamespaces_UseWithFullyQualifiedNS03()  { $this->generic_test('Namespaces/UseWithFullyQualifiedNS.03'); }
}
?>