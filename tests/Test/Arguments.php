<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Arguments extends Tokenizeur {
    /* 7 methods */

    public function testArguments01()  { $this->generic_test('Arguments.01'); }
    public function testArguments02()  { $this->generic_test('Arguments.02'); }
    public function testArguments03()  { $this->generic_test('Arguments.03'); }
    public function testArguments04()  { $this->generic_test('Arguments.04'); }
    public function testArguments05()  { $this->generic_test('Arguments.05'); }
    public function testArguments06()  { $this->generic_test('Arguments.06'); }
    public function testArguments07()  { $this->generic_test('Arguments.07'); }
}
?>