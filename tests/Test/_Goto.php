<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Goto extends Tokenizeur {
    /* 4 methods */

    public function test_Goto01()  { $this->generic_test('_Goto.01'); }
    public function test_Goto02()  { $this->generic_test('_Goto.02'); }
    public function test_Goto03()  { $this->generic_test('_Goto.03'); }
    public function test_Goto04()  { $this->generic_test('_Goto.04'); }
}
?>