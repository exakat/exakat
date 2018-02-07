<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_StrangeName extends Analyzer {
    /* 3 methods */

    public function testConstants_StrangeName01()  { $this->generic_test('Constants/StrangeName.01'); }
    public function testConstants_StrangeName02()  { $this->generic_test('Constants/StrangeName.02'); }
    public function testConstants_StrangeName03()  { $this->generic_test('Constants/StrangeName.03'); }
}
?>