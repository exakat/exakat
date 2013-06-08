<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Sign extends Tokenizeur {
    /* 10 methods */
    public function testSign01()  { $this->generic_test('Sign.01'); }
    public function testSign02()  { $this->generic_test('Sign.02'); }
    public function testSign03()  { $this->generic_test('Sign.03'); }
    public function testSign04()  { $this->generic_test('Sign.04'); }
    public function testSign05()  { $this->generic_test('Sign.05'); }
    public function testSign06()  { $this->generic_test('Sign.06'); }
    public function testSign07()  { $this->generic_test('Sign.07'); }
    public function testSign08()  { $this->generic_test('Sign.08'); }
    public function testSign09()  { $this->generic_test('Sign.09'); }
    public function testSign10()  { $this->generic_test('Sign.10'); }
}
?>