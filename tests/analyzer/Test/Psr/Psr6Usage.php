<?php

namespace Test\Psr;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Psr6Usage extends Analyzer {
    /* 1 methods */

    public function testPsr_Psr6Usage01()  { $this->generic_test('Psr/Psr6Usage.01'); }
}
?>