<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Interfaces_CouldUseInterface extends Analyzer {
    /* 5 methods */

    public function testInterfaces_CouldUseInterface01()  { $this->generic_test('Interfaces/CouldUseInterface.01'); }
    public function testInterfaces_CouldUseInterface02()  { $this->generic_test('Interfaces/CouldUseInterface.02'); }
    public function testInterfaces_CouldUseInterface03()  { $this->generic_test('Interfaces/CouldUseInterface.03'); }
    public function testInterfaces_CouldUseInterface04()  { $this->generic_test('Interfaces/CouldUseInterface.04'); }
    public function testInterfaces_CouldUseInterface05()  { $this->generic_test('Interfaces/CouldUseInterface.05'); }
}
?>