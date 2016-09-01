<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_LoopCalling extends Analyzer {
    /* 3 methods */

    public function testFunctions_LoopCalling01()  { $this->generic_test('Functions/LoopCalling.01'); }
    public function testFunctions_LoopCalling02()  { $this->generic_test('Functions/LoopCalling.02'); }
    public function testFunctions_LoopCalling03()  { $this->generic_test('Functions/LoopCalling.03'); }
}
?>