<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Include extends Tokenizer {
    /* 24 methods */

    public function test_Include01()  { $this->generic_test('_Include.01'); }
    public function test_Include02()  { $this->generic_test('_Include.02'); }
    public function test_Include03()  { $this->generic_test('_Include.03'); }
    public function test_Include04()  { $this->generic_test('_Include.04'); }
    public function test_Include05()  { $this->generic_test('_Include.05'); }
    public function test_Include06()  { $this->generic_test('_Include.06'); }
    public function test_Include07()  { $this->generic_test('_Include.07'); }
    public function test_Include08()  { $this->generic_test('_Include.08'); }
    public function test_Include09()  { $this->generic_test('_Include.09'); }
    public function test_Include10()  { $this->generic_test('_Include.10'); }
    public function test_Include11()  { $this->generic_test('_Include.11'); }
    public function test_Include12()  { $this->generic_test('_Include.12'); }
    public function test_Include13()  { $this->generic_test('_Include.13'); }
    public function test_Include14()  { $this->generic_test('_Include.14'); }
    public function test_Include15()  { $this->generic_test('_Include.15'); }
    public function test_Include16()  { $this->generic_test('_Include.16'); }
    public function test_Include17()  { $this->generic_test('_Include.17'); }
    public function test_Include18()  { $this->generic_test('_Include.18'); }
    public function test_Include19()  { $this->generic_test('_Include.19'); }
    public function test_Include20()  { $this->generic_test('_Include.20'); }
    public function test_Include21()  { $this->generic_test('_Include.21'); }
    public function test_Include22()  { $this->generic_test('_Include.22'); }
    public function test_Include23()  { $this->generic_test('_Include.23'); }
    public function test_Include24()  { $this->generic_test('_Include.24'); }
}
?>