<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CouldTypehint extends Analyzer {
    /* 1 methods */

    public function testFunctions_CouldTypehint01()  { $this->generic_test('Functions/CouldTypehint.01'); }
}
?>