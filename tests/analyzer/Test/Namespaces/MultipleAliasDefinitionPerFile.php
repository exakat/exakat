<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 2).'/Test/Analyzer.php';

class MultipleAliasDefinitionPerFile extends Analyzer {
    /* 6 methods */

    public function testNamespaces_MultipleAliasDefinitionPerFile01()  { $this->generic_test('Namespaces/MultipleAliasDefinitionPerFile.01'); }
    public function testNamespaces_MultipleAliasDefinitionPerFile02()  { $this->generic_test('Namespaces/MultipleAliasDefinitionPerFile.02'); }
    public function testNamespaces_MultipleAliasDefinitionPerFile03()  { $this->generic_test('Namespaces/MultipleAliasDefinitionPerFile.03'); }
    public function testNamespaces_MultipleAliasDefinitionPerFile04()  { $this->generic_test('Namespaces/MultipleAliasDefinitionPerFile.04'); }
    public function testNamespaces_MultipleAliasDefinitionPerFile05()  { $this->generic_test('Namespaces/MultipleAliasDefinitionPerFile.05'); }
    public function testNamespaces_MultipleAliasDefinitionPerFile06()  { $this->generic_test('Namespaces/MultipleAliasDefinitionPerFile.06'); }
}
?>