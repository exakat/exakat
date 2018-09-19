<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NoReturnUsed extends Analyzer {
    /* 4 methods */

    public function testFunctions_NoReturnUsed01()  { $this->generic_test('Functions/NoReturnUsed.01'); }
    public function testFunctions_NoReturnUsed02()  { $this->generic_test('Functions/NoReturnUsed.02'); }
    public function testFunctions_NoReturnUsed03()  { $this->generic_test('Functions/NoReturnUsed.03'); }
    public function testFunctions_NoReturnUsed04()  { $this->generic_test('Functions/NoReturnUsed.04'); }
}
?>