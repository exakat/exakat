<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extevent extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extevent01()  { $this->generic_test('Extensions/Extevent.01'); }
}
?>