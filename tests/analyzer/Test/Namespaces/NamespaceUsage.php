<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NamespaceUsage extends Analyzer {
    /* 2 methods */

    public function testNamespaces_NamespaceUsage01()  { $this->generic_test('Namespaces/NamespaceUsage.01'); }
    public function testNamespaces_NamespaceUsage02()  { $this->generic_test('Namespaces/NamespaceUsage.02'); }
}
?>