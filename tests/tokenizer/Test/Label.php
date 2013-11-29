<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Label extends Tokenizer {
    /* 9 methods */

    public function testLabel01()  { $this->generic_test('Label.01'); }
    public function testLabel02()  { $this->generic_test('Label.02'); }
    public function testLabel03()  { $this->generic_test('Label.03'); }
    public function testLabel04()  { $this->generic_test('Label.04'); }
    public function testLabel05()  { $this->generic_test('Label.05'); }
    public function testLabel06()  { $this->generic_test('Label.06'); }
    public function testLabel07()  { $this->generic_test('Label.07'); }
    public function testLabel08()  { $this->generic_test('Label.08'); }
    public function testLabel09()  { $this->generic_test('Label.09'); }
}
?>