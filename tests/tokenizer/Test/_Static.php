<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Static extends Tokenizer {
    /* 20 methods */

    public function test_Static01()  { $this->generic_test('_Static.01'); }
    public function test_Static02()  { $this->generic_test('_Static.02'); }
    public function test_Static03()  { $this->generic_test('_Static.03'); }
    public function test_Static04()  { $this->generic_test('_Static.04'); }
    public function test_Static05()  { $this->generic_test('_Static.05'); }
    public function test_Static06()  { $this->generic_test('_Static.06'); }
    public function test_Static07()  { $this->generic_test('_Static.07'); }
    public function test_Static08()  { $this->generic_test('_Static.08'); }
    public function test_Static09()  { $this->generic_test('_Static.09'); }
    public function test_Static10()  { $this->generic_test('_Static.10'); }
    public function test_Static11()  { $this->generic_test('_Static.11'); }
    public function test_Static12()  { $this->generic_test('_Static.12'); }

    public function test_Static13()  { $this->generic_test('_Static.13'); }
    public function test_Static14()  { $this->generic_test('_Static.14'); }
    public function test_Static15()  { $this->generic_test('_Static.15'); }
    public function test_Static16()  { $this->generic_test('_Static.16'); }
    public function test_Static17()  { $this->generic_test('_Static.17'); }
    public function test_Static18()  { $this->generic_test('_Static.18'); }
    public function test_Static19()  { $this->generic_test('_Static.19'); }
    public function test_Static20()  { $this->generic_test('_Static.20'); }
}
?>