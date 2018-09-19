<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NoClassAsTypehint extends Analyzer {
    /* 3 methods */

    public function testFunctions_NoClassAsTypehint01()  { $this->generic_test('Functions/NoClassAsTypehint.01'); }
    public function testFunctions_NoClassAsTypehint02()  { $this->generic_test('Functions/NoClassAsTypehint.02'); }
    public function testFunctions_NoClassAsTypehint03()  { $this->generic_test('Functions/NoClassAsTypehint.03'); }
}
?>