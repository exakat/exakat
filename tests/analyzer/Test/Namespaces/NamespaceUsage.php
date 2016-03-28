<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_NamespaceUsage extends Analyzer {
    /* 4 methods */

    public function testNamespaces_NamespaceUsage01()  { $this->generic_test('Namespaces/NamespaceUsage.01'); }
    public function testNamespaces_NamespaceUsage02()  { $this->generic_test('Namespaces/NamespaceUsage.02'); }
    public function testNamespaces_NamespaceUsage03()  { $this->generic_test('Namespaces/NamespaceUsage.03'); }
    public function testNamespaces_NamespaceUsage04()  { $this->generic_test('Namespaces/NamespaceUsage.04'); }
}
?>