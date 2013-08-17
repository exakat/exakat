<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Namespace extends Tokenizeur {
    /* 4 methods */

    public function test_Namespace01()  { $this->generic_test('_Namespace.01'); }
    public function test_Namespace02()  { $this->generic_test('_Namespace.02'); }
    public function test_Namespace03()  { $this->generic_test('_Namespace.03'); }
    public function test_Namespace04()  { $this->generic_test('_Namespace.04'); }
}
?>