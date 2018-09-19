<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UsedUse extends Analyzer {
    /* 12 methods */

    public function testNamespaces_UsedUse01()  { $this->generic_test('Namespaces_UsedUse.01'); }
    public function testNamespaces_UsedUse02()  { $this->generic_test('Namespaces_UsedUse.02'); }
    public function testNamespaces_UsedUse03()  { $this->generic_test('Namespaces_UsedUse.03'); }
    public function testNamespaces_UsedUse04()  { $this->generic_test('Namespaces_UsedUse.04'); }
    public function testNamespaces_UsedUse05()  { $this->generic_test('Namespaces_UsedUse.05'); }
    public function testNamespaces_UsedUse06()  { $this->generic_test('Namespaces_UsedUse.06'); }
    public function testNamespaces_UsedUse07()  { $this->generic_test('Namespaces_UsedUse.07'); }
    public function testNamespaces_UsedUse08()  { $this->generic_test('Namespaces/UsedUse.08'); }
    public function testNamespaces_UsedUse09()  { $this->generic_test('Namespaces/UsedUse.09'); }
    public function testNamespaces_UsedUse10()  { $this->generic_test('Namespaces/UsedUse.10'); }
    public function testNamespaces_UsedUse11()  { $this->generic_test('Namespaces/UsedUse.11'); }
    public function testNamespaces_UsedUse12()  { $this->generic_test('Namespaces/UsedUse.12'); }
}
?>