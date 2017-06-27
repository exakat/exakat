<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_OrderOfDeclaration extends Analyzer {
    /* 3 methods */

    public function testClasses_OrderOfDeclaration01()  { $this->generic_test('Classes/OrderOfDeclaration.01'); }
    public function testClasses_OrderOfDeclaration02()  { $this->generic_test('Classes/OrderOfDeclaration.02'); }
    public function testClasses_OrderOfDeclaration03()  { $this->generic_test('Classes/OrderOfDeclaration.03'); }
}
?>