<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Magicconstant extends Tokenizer {
    /* 2 methods */

    public function testMagicconstant01()  { $this->generic_test('Magicconstant.01'); }
    public function testMagicConstant01()  { $this->generic_test('MagicConstant.01'); }
    public function testMagicConstant01()  { $this->generic_test('MagicConstant.01'); }
    public function testMagicconstant02()  { $this->generic_test('Magicconstant.02'); }
}
?>