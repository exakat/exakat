<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Const extends Tokenizeur {
    /* 2 methods */

    public function test_Const01()  { $this->generic_test('_Const.01'); }
    public function test_Const02()  { $this->generic_test('_Const.02'); }
}
?>