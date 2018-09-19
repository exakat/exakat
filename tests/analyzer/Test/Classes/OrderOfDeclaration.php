<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class OrderOfDeclaration extends Analyzer {
    /* 3 methods */

    public function testClasses_OrderOfDeclaration01()  { $this->generic_test('Classes/OrderOfDeclaration.01'); }
    public function testClasses_OrderOfDeclaration02()  { $this->generic_test('Classes/OrderOfDeclaration.02'); }
    public function testClasses_OrderOfDeclaration03()  { $this->generic_test('Classes/OrderOfDeclaration.03'); }
}
?>