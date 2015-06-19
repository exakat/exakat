<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Precedence extends Tokenizer {
    /* 7 methods */

    public function testPrecedence01()  { $this->generic_test('Precedence.01'); }
    public function testPrecedence02()  { $this->generic_test('Precedence.02'); }
    public function testPrecedence03()  { $this->generic_test('Precedence.03'); }
    public function testPrecedence04()  { $this->generic_test('Precedence.04'); }
    public function testPrecedence05()  { $this->generic_test('Precedence.05'); }
    public function testPrecedence06()  { $this->generic_test('Precedence.06'); }
    public function testPrecedence07()  { $this->generic_test('Precedence.07'); }
}
?>