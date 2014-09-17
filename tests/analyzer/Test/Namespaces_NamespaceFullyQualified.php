<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_NamespaceFullyQualified extends Analyzer {
    /* 3 methods */

    public function testNamespaces_NamespaceFullyQualified01()  { $this->generic_test('Namespaces_NamespaceFullyQualified.01'); }
    public function testNamespaces_NamespaceFullyQualified02()  { $this->generic_test('Namespaces_NamespaceFullyQualified.02'); }
    public function testNamespaces_NamespaceFullyQualified03()  { $this->generic_test('Namespaces_NamespaceFullyQualified.03'); }
}
?>