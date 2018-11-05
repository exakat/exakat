<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Functioncall2 extends Tokenizer {
    /* 9 methods */

    public function testFunctioncall201()  { $this->generic_test('Functioncall2.01'); }
    public function testFunctioncall202()  { $this->generic_test('Functioncall2.02'); }
    public function testFunctioncall203()  { $this->generic_test('Functioncall2.03'); }
    public function testFunctioncall204()  { $this->generic_test('Functioncall2.04'); }
    public function testFunctioncall205()  { $this->generic_test('Functioncall2.05'); }
    public function testFunctioncall206()  { $this->generic_test('Functioncall2.06'); }
    public function testFunctioncall207()  { $this->generic_test('Functioncall2.07'); }
    public function testFunctioncall208()  { $this->generic_test('Functioncall2.08'); }
    public function testFunctioncall209()  { $this->generic_test('Functioncall2.09'); }
}
?>