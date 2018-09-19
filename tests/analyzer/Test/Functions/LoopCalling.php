<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class LoopCalling extends Analyzer {
    /* 9 methods */

    public function testFunctions_LoopCalling01()  { $this->generic_test('Functions/LoopCalling.01'); }
    public function testFunctions_LoopCalling02()  { $this->generic_test('Functions/LoopCalling.02'); }
    public function testFunctions_LoopCalling03()  { $this->generic_test('Functions/LoopCalling.03'); }
    public function testFunctions_LoopCalling04()  { $this->generic_test('Functions/LoopCalling.04'); }
    public function testFunctions_LoopCalling05()  { $this->generic_test('Functions/LoopCalling.05'); }
    public function testFunctions_LoopCalling06()  { $this->generic_test('Functions/LoopCalling.06'); }
    public function testFunctions_LoopCalling07()  { $this->generic_test('Functions/LoopCalling.07'); }
    public function testFunctions_LoopCalling08()  { $this->generic_test('Functions/LoopCalling.08'); }
    public function testFunctions_LoopCalling09()  { $this->generic_test('Functions/LoopCalling.09'); }
}
?>