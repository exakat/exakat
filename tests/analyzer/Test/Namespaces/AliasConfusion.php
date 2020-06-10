<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class AliasConfusion extends Analyzer {
    /* 2 methods */

    public function testNamespaces_AliasConfusion01()  { $this->generic_test('Namespaces/AliasConfusion.01'); }
    public function testNamespaces_AliasConfusion02()  { $this->generic_test('Namespaces/AliasConfusion.02'); }
}
?>