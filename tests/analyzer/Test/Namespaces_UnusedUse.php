<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_UnusedUse extends Analyzer {
    /* 5 methods */

    public function testNamespaces_UnusedUse01()  { $this->generic_test('Namespaces_UnusedUse.01'); }
    public function testNamespaces_UnusedUse02()  { $this->generic_test('Namespaces_UnusedUse.02'); }
    public function testNamespaces_UnusedUse03()  { $this->generic_test('Namespaces_UnusedUse.03'); }
    public function testNamespaces_UnusedUse04()  { $this->generic_test('Namespaces_UnusedUse.04'); }
    public function testNamespaces_UnusedUse05()  { $this->generic_test('Namespaces_UnusedUse.05'); }
}
?>