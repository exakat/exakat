<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class GlobalsVsGlobal extends Analyzer {
    /* 3 methods */

    public function testPhp_GlobalsVsGlobal01()  { $this->generic_test('Php/GlobalsVsGlobal.01'); }
    public function testPhp_GlobalsVsGlobal02()  { $this->generic_test('Php/GlobalsVsGlobal.02'); }
    public function testPhp_GlobalsVsGlobal03()  { $this->generic_test('Php/GlobalsVsGlobal.03'); }
}
?>