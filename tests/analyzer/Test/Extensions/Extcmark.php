<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extensions_Extcmark extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extcmark01()  { $this->generic_test('Extensions/Extcmark.01'); }
    public function testExtensions_Extcmark02()  { $this->generic_test('Extensions/Extcmark.02'); }
}
?>