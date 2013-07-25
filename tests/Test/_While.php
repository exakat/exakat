<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _While extends Tokenizeur {
    /* 15 methods */

    public function test_While01()  { $this->generic_test('_While.01'); }
    public function test_While02()  { $this->generic_test('_While.02'); }
    public function test_While03()  { $this->generic_test('_While.03'); }
    public function test_While04()  { $this->generic_test('_While.04'); }
    public function test_While05()  { $this->generic_test('_While.05'); }
    public function test_While06()  { $this->generic_test('_While.06'); }
    public function test_While07()  { $this->generic_test('_While.07'); }
    public function test_While08()  { $this->generic_test('_While.08'); }
    public function test_While09()  { $this->generic_test('_While.09'); }
    public function test_While10()  { $this->generic_test('_While.10'); }
    public function test_While11()  { $this->generic_test('_While.11'); }
    public function test_While12()  { $this->generic_test('_While.12'); }
    public function test_While13()  { $this->generic_test('_While.13'); }
    public function test_While14()  { $this->generic_test('_While.14'); }
    public function test_While15()  { $this->generic_test('_While.15'); }
}
?>