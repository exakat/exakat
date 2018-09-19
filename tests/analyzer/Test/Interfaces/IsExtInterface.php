<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class IsExtInterface extends Analyzer {
    /* 4 methods */

    public function testInterfaces_IsExtInterface01()  { $this->generic_test('Interfaces_IsExtInterface.01'); }
    public function testInterfaces_IsExtInterface02()  { $this->generic_test('Interfaces_IsExtInterface.02'); }
    public function testInterfaces_IsExtInterface03()  { $this->generic_test('Interfaces/IsExtInterface.03'); }
    public function testInterfaces_IsExtInterface04()  { $this->generic_test('Interfaces/IsExtInterface.04'); }
}
?>