<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Foreach extends Tokenizeur {
    /* 4 methods */

    public function test_Foreach01()  { $this->generic_test('_Foreach.01'); }
    public function test_Foreach02()  { $this->generic_test('_Foreach.02'); }
    public function test_Foreach03()  { $this->generic_test('_Foreach.03'); }
    public function test_Foreach04()  { $this->generic_test('_Foreach.04'); }
}
?>