<?php

namespace Test\Traits;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php extends Analyzer {
    /* 1 methods */

    public function testTraits_Php01()  { $this->generic_test('Traits_Php.01'); }
}
?>