<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Namespaces_Namespacesnames extends Analyzer {
    /* 4 methods */

    public function testNamespaces_Namespacesnames01()  { $this->generic_test('Namespaces_Namespacesnames.01'); }
    public function testNamespaces_Namespacesnames02()  { $this->generic_test('Namespaces_Namespacesnames.02'); }
    public function testNamespaces_Namespacesnames03()  { $this->generic_test('Namespaces_Namespacesnames.03'); }
    public function testNamespaces_Namespacesnames04()  { $this->generic_test('Namespaces_Namespacesnames.04'); }
}
?>