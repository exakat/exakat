<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Extensions_Extgd extends Analyzer {
    /* 4 methods */

    public function testExtensions_Extgd01()  { $this->generic_test('Extensions_Extgd.01'); }
    public function testExtensions_Extgd02()  { $this->generic_test('Extensions_Extgd.02'); }
    public function testExtensions_Extgd03()  { $this->generic_test('Extensions_Extgd.03'); }
    public function testExtensions_Extgd04()  { $this->generic_test('Extensions_Extgd.04'); }
}
?>