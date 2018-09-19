<?php

namespace Test\Arrays;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class NonConstantArray extends Analyzer {
    /* 5 methods */

    public function testArrays_NonConstantArray01()  { $this->generic_test('Arrays/NonConstantArray.01'); }
    public function testArrays_NonConstantArray02()  { $this->generic_test('Arrays/NonConstantArray.02'); }
    public function testArrays_NonConstantArray03()  { $this->generic_test('Arrays/NonConstantArray.03'); }
    public function testArrays_NonConstantArray04()  { $this->generic_test('Arrays/NonConstantArray.04'); }
    public function testArrays_NonConstantArray05()  { $this->generic_test('Arrays/NonConstantArray.05'); }
}
?>