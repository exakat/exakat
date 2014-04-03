<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extpdo extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extpdo01()  { $this->generic_test('Extensions_Extpdo.01'); }
    public function testExtensions_Extpdo02()  { $this->generic_test('Extensions_Extpdo.02'); }
}
?>