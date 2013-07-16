<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Try extends Tokenizeur {
    /* 5 methods */

    public function test_Try01()  { $this->generic_test('_Try.01'); }
    public function test_Try02()  { $this->generic_test('_Try.02'); }
    public function test_Try03()  { $this->generic_test('_Try.03'); }
    public function test_Try04()  { $this->generic_test('_Try.04'); }
    public function test_Try05()  { $this->generic_test('_Try.05'); }
}
?>