<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Constant extends Tokenizeur {
    /* 2 methods */
    public function testConstant01()  { $this->generic_test('Constant.01'); }
    public function testConstant02()  { $this->generic_test('Constant.02'); }
}
?>