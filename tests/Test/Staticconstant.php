<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Staticconstant extends Tokenizeur {
    /* 4 methods */

    public function testStaticconstant01()  { $this->generic_test('Staticconstant.01'); }
    public function testStaticconstant02()  { $this->generic_test('Staticconstant.02'); }
    public function testStaticconstant03()  { $this->generic_test('Staticconstant.03'); }
    public function testStaticconstant04()  { $this->generic_test('Staticconstant.04'); }
}
?>