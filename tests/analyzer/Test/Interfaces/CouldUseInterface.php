<?php

namespace Test\Interfaces;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CouldUseInterface extends Analyzer {
    /* 5 methods */

    public function testInterfaces_CouldUseInterface01()  { $this->generic_test('Interfaces/CouldUseInterface.01'); }
    public function testInterfaces_CouldUseInterface02()  { $this->generic_test('Interfaces/CouldUseInterface.02'); }
    public function testInterfaces_CouldUseInterface03()  { $this->generic_test('Interfaces/CouldUseInterface.03'); }
    public function testInterfaces_CouldUseInterface04()  { $this->generic_test('Interfaces/CouldUseInterface.04'); }
    public function testInterfaces_CouldUseInterface05()  { $this->generic_test('Interfaces/CouldUseInterface.05'); }
}
?>