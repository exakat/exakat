<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extcmark extends Analyzer {
    /* 2 methods */

    public function testExtensions_Extcmark01()  { $this->generic_test('Extensions/Extcmark.01'); }
    public function testExtensions_Extcmark02()  { $this->generic_test('Extensions/Extcmark.02'); }
}
?>