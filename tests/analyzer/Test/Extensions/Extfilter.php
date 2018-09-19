<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extfilter extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extfilter01()  { $this->generic_test('Extensions_Extfilter.01'); }
}
?>