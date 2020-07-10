<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Match extends Tokenizer {
    /* 21 methods */

    public function testMatch01()  { $this->generic_test('Match.01'); }
    public function testMatch02()  { $this->generic_test('Match.02'); }
    public function testMatch03()  { $this->generic_test('Match.03'); }
    public function testMatch04()  { $this->generic_test('Match.04'); }
    public function testMatch05()  { $this->generic_test('Match.05'); }
    public function testMatch06()  { $this->generic_test('Match.06'); }
    public function testMatch07()  { $this->generic_test('Match.07'); }
    public function testMatch08()  { $this->generic_test('Match.08'); }
    public function testMatch09()  { $this->generic_test('Match.09'); }
    public function testMatch10()  { $this->generic_test('Match.10'); }
    public function testMatch11()  { $this->generic_test('Match.11'); }
    public function testMatch12()  { $this->generic_test('Match.12'); }
    public function testMatch13()  { $this->generic_test('Match.13'); }
    public function testMatch14()  { $this->generic_test('Match.14'); }
    public function testMatch15()  { $this->generic_test('Match.15'); }
    public function testMatch16()  { $this->generic_test('Match.16'); }
    public function testMatch17()  { $this->generic_test('Match.17'); }
    public function testMatch18()  { $this->generic_test('Match.18'); }
    public function testMatch19()  { $this->generic_test('Match.19'); }
    public function testMatch20()  { $this->generic_test('Match.20'); }
    public function testMatch21()  { $this->generic_test('Match.21'); }
}
?>