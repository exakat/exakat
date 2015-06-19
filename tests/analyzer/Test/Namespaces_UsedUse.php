<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Namespaces_UsedUse extends Analyzer {
    /* 7 methods */

    public function testNamespaces_UsedUse01()  { $this->generic_test('Namespaces_UsedUse.01'); }
    public function testNamespaces_UsedUse02()  { $this->generic_test('Namespaces_UsedUse.02'); }
    public function testNamespaces_UsedUse03()  { $this->generic_test('Namespaces_UsedUse.03'); }
    public function testNamespaces_UsedUse04()  { $this->generic_test('Namespaces_UsedUse.04'); }
    public function testNamespaces_UsedUse05()  { $this->generic_test('Namespaces_UsedUse.05'); }
    public function testNamespaces_UsedUse06()  { $this->generic_test('Namespaces_UsedUse.06'); }
    public function testNamespaces_UsedUse07()  { $this->generic_test('Namespaces_UsedUse.07'); }
}
?>