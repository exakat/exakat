<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class ArrayNS extends Tokenizeur {
    /* 12 methods */

    public function testArrayNS01()  { $this->generic_test('ArrayNS.01'); }
    public function testArrayNS02()  { $this->generic_test('ArrayNS.02'); }
    public function testArrayNS03()  { $this->generic_test('ArrayNS.03'); }
    public function testArrayNS04()  { $this->generic_test('ArrayNS.04'); }
    public function testArrayNS05()  { $this->generic_test('ArrayNS.05'); }
    public function testArrayNS06()  { $this->generic_test('ArrayNS.06'); }
    public function testArrayNS07()  { $this->generic_test('ArrayNS.07'); }
    public function testArrayNS08()  { $this->generic_test('ArrayNS.08'); }
    public function testArrayNS09()  { $this->generic_test('ArrayNS.09'); }
    public function testArrayNS10()  { $this->generic_test('ArrayNS.10'); }
    public function testArrayNS11()  { $this->generic_test('ArrayNS.11'); }
    public function testArrayNS12()  { $this->generic_test('ArrayNS.12'); }
}
?>