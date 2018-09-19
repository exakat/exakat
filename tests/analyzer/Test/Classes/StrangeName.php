<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class StrangeName extends Analyzer {
    /* 1 methods */

    public function testClasses_StrangeName01()  { $this->generic_test('Classes/StrangeName.01'); }
}
?>