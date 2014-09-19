<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_UnresolvedUse extends Analyzer {
    /* 5 methods */

    public function testNamespaces_UnresolvedUse01()  { $this->generic_test('Namespaces_UnresolvedUse.01'); }
    public function testNamespaces_UnresolvedUse02()  { $this->generic_test('Namespaces_UnresolvedUse.02'); }
    public function testNamespaces_UnresolvedUse03()  { $this->generic_test('Namespaces_UnresolvedUse.03'); }
    public function testNamespaces_UnresolvedUse04()  { $this->generic_test('Namespaces_UnresolvedUse.04'); }
    public function testNamespaces_UnresolvedUse05()  { $this->generic_test('Namespaces_UnresolvedUse.05'); }
}
?>