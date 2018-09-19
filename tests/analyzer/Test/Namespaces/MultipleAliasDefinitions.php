<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class MultipleAliasDefinitions extends Analyzer {
    /* 1 methods */

    public function testNamespaces_MultipleAliasDefinitions01()  { $this->generic_test('Namespaces/MultipleAliasDefinitions.01'); }
}
?>