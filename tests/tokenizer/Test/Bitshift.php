<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Bitshift extends Tokenizer {
    /* 10 methods */

    public function testBitshift01()  { $this->generic_test('Bitshift.01'); }
    public function testBitshift02()  { $this->generic_test('Bitshift.02'); }
    public function testBitshift03()  { $this->generic_test('Bitshift.03'); }
    public function testBitshift04()  { $this->generic_test('Bitshift.04'); }
    public function testBitshift05()  { $this->generic_test('Bitshift.05'); }
    public function testBitshift06()  { $this->generic_test('Bitshift.06'); }
    public function testBitshift07()  { $this->generic_test('Bitshift.07'); }
    public function testBitshift08()  { $this->generic_test('Bitshift.08'); }
    public function testBitshift09()  { $this->generic_test('Bitshift.09'); }
    public function testBitshift10()  { $this->generic_test('Bitshift.10'); }
}
?>