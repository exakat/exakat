<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Interfaces_Php extends Analyzer {
    /* 3 methods */

    public function testInterfaces_Php01()  { $this->generic_test('Interfaces_Php.01'); }
    public function testInterfaces_Php02()  { $this->generic_test('Interfaces_Php.02'); }
    public function testInterfaces_Php03()  { $this->generic_test('Interfaces_Php.03'); }
}
?>