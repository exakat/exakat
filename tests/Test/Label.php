<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Label extends Tokenizeur {
    /* 6 methods */

    public function testLabel01()  { $this->generic_test('Label.01'); }
    public function testLabel02()  { $this->generic_test('Label.02'); }
    public function testLabel03()  { $this->generic_test('Label.03'); }
    public function testLabel04()  { $this->generic_test('Label.04'); }
    public function testLabel05()  { $this->generic_test('Label.05'); }
    public function testLabel06()  { $this->generic_test('Label.06'); }
}
?>