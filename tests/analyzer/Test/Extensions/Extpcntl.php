<?php

namespace Test\Extensions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Extpcntl extends Analyzer {
    /* 1 methods */

    public function testExtensions_Extpcntl01()  { $this->generic_test('Extensions_Extpcntl.01'); }
}
?>