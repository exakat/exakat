<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_MultipleConstantDefinition extends Analyzer {
    /* 4 methods */

    public function testConstants_MultipleConstantDefinition01()  { $this->generic_test('Constants_MultipleConstantDefinition.01'); }
    public function testConstants_MultipleConstantDefinition02()  { $this->generic_test('Constants/MultipleConstantDefinition.02'); }
    public function testConstants_MultipleConstantDefinition03()  { $this->generic_test('Constants/MultipleConstantDefinition.03'); }
    public function testConstants_MultipleConstantDefinition04()  { $this->generic_test('Constants/MultipleConstantDefinition.04'); }
}
?>