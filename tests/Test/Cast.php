<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Cast extends Tokenizeur {
    /* 8 methods */

    public function testCast01()  { $this->generic_test('Cast.01'); }
    public function testCast02()  { $this->generic_test('Cast.02'); }
    public function testCast03()  { $this->generic_test('Cast.03'); }
    public function testCast04()  { $this->generic_test('Cast.04'); }
    public function testCast05()  { $this->generic_test('Cast.05'); }
    public function testCast06()  { $this->generic_test('Cast.06'); }
    public function testCast07()  { $this->generic_test('Cast.07'); }
    public function testCast08()  { $this->generic_test('Cast.08'); }
}
?>