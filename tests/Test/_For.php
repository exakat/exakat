<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _For extends Tokenizeur {
    /* 16 methods */

    public function test_For01()  { $this->generic_test('_For.01'); }
    public function test_For02()  { $this->generic_test('_For.02'); }
    public function test_For03()  { $this->generic_test('_For.03'); }
    public function test_For04()  { $this->generic_test('_For.04'); }
    public function test_For05()  { $this->generic_test('_For.05'); }
    public function test_For06()  { $this->generic_test('_For.06'); }
    public function test_For07()  { $this->generic_test('_For.07'); }
    public function test_For08()  { $this->generic_test('_For.08'); }
    public function test_For09()  { $this->generic_test('_For.09'); }
    public function test_For10()  { $this->generic_test('_For.10'); }
    public function test_For11()  { $this->generic_test('_For.11'); }
    public function test_For12()  { $this->generic_test('_For.12'); }
    public function test_For13()  { $this->generic_test('_For.13'); }
    public function test_For14()  { $this->generic_test('_For.14'); }
    public function test_For15()  { $this->generic_test('_For.15'); }
    public function test_For16()  { $this->generic_test('_For.16'); }
}
?>