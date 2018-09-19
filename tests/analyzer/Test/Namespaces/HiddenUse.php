<?php

namespace Test\Namespaces;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class HiddenUse extends Analyzer {
    /* 5 methods */

    public function testNamespaces_HiddenUse01()  { $this->generic_test('Namespaces/HiddenUse.01'); }
    public function testNamespaces_HiddenUse02()  { $this->generic_test('Namespaces/HiddenUse.02'); }
    public function testNamespaces_HiddenUse03()  { $this->generic_test('Namespaces/HiddenUse.03'); }
    public function testNamespaces_HiddenUse04()  { $this->generic_test('Namespaces/HiddenUse.04'); }
    public function testNamespaces_HiddenUse05()  { $this->generic_test('Namespaces/HiddenUse.05'); }
}
?>