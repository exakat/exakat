<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_EmptyNamespace extends Analyzer {
    /* 3 methods */

    public function testNamespaces_EmptyNamespace01()  { $this->generic_test('Namespaces_EmptyNamespace.01'); }
    public function testNamespaces_EmptyNamespace02()  { $this->generic_test('Namespaces_EmptyNamespace.02'); }
    public function testNamespaces_EmptyNamespace03()  { $this->generic_test('Namespaces_EmptyNamespace.03'); }
}
?>