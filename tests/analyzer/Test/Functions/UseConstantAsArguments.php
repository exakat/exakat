<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_UseConstantAsArguments extends Analyzer {
    /* 8 methods */

    public function testFunctions_UseConstantAsArguments01()  { $this->generic_test('Functions_UseConstantAsArguments.01'); }
    public function testFunctions_UseConstantAsArguments02()  { $this->generic_test('Functions_UseConstantAsArguments.02'); }
    public function testFunctions_UseConstantAsArguments03()  { $this->generic_test('Functions_UseConstantAsArguments.03'); }
    public function testFunctions_UseConstantAsArguments04()  { $this->generic_test('Functions_UseConstantAsArguments.04'); }
    public function testFunctions_UseConstantAsArguments05()  { $this->generic_test('Functions/UseConstantAsArguments.05'); }
    public function testFunctions_UseConstantAsArguments06()  { $this->generic_test('Functions/UseConstantAsArguments.06'); }
    public function testFunctions_UseConstantAsArguments07()  { $this->generic_test('Functions/UseConstantAsArguments.07'); }
    public function testFunctions_UseConstantAsArguments08()  { $this->generic_test('Functions/UseConstantAsArguments.08'); }
}
?>