<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extming extends Analyzer {
    /* 3 methods */

    public function testExtensions_Extming01()  { $this->generic_test('Extensions_Extming.01'); }
    public function testExtensions_Extming02()  { $this->generic_test('Extensions/Extming.02'); }
    public function testExtensions_Extming03()  { $this->generic_test('Extensions/Extming.03'); }
}
?>