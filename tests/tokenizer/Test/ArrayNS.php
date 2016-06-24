<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class ArrayNS extends Tokenizer {
    /* 26 methods */

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
    public function testArrayNS13()  { $this->generic_test('ArrayNS.13'); }
    public function testArrayNS14()  { $this->generic_test('ArrayNS.14'); }
    public function testArrayNS15()  { $this->generic_test('ArrayNS.15'); }
    public function testArrayNS16()  { $this->generic_test('ArrayNS.16'); }
    public function testArrayNS17()  { $this->generic_test('ArrayNS.17'); }
    public function testArrayNS18()  { $this->generic_test('ArrayNS.18'); }
    public function testArrayNS19()  { $this->generic_test('ArrayNS.19'); }
    public function testArrayNS20()  { $this->generic_test('ArrayNS.20'); }
    public function testArrayNS21()  { $this->generic_test('ArrayNS.21'); }
    public function testArrayNS22()  { $this->generic_test('ArrayNS.22'); }
    public function testArrayNS23()  { $this->generic_test('ArrayNS.23'); }
    public function testArrayNS24()  { $this->generic_test('ArrayNS.24'); }
    public function testArrayNS25()  { $this->generic_test('ArrayNS.25'); }
    public function testArrayNS26()  { $this->generic_test('ArrayNS.26'); }
}
?>