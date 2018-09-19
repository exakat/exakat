<?php

namespace Test\Php;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Php71NewFunctions extends Analyzer {
    /* 1 methods */

    public function testPhp_Php71NewFunctions01()  { $this->generic_test('Php/Php71NewFunctions.01'); }
}
?>