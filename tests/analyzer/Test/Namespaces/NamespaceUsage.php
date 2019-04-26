<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class NamespaceUsage extends Analyzer {
    /* 2 methods */

    public function testNamespaces_NamespaceUsage01()  { $this->generic_test('Namespaces/NamespaceUsage.01'); }
    public function testNamespaces_NamespaceUsage02()  { $this->generic_test('Namespaces/NamespaceUsage.02'); }
}
?>