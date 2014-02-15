<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Use extends Tokenizer {
    /* 9 methods */

    public function test_Use01()  { $this->generic_test('_Use.01'); }
    public function test_Use02()  { $this->generic_test('_Use.02'); }
    public function test_Use03()  { $this->generic_test('_Use.03'); }
    public function test_Use04()  { $this->generic_test('_Use.04'); }
    public function test_Use05()  { $this->generic_test('_Use.05'); }
    public function test_Use06()  { $this->generic_test('_Use.06'); }
    public function test_Use07()  { $this->generic_test('_Use.07'); }
    public function test_Use08()  { $this->generic_test('_Use.08'); }
    public function test_Use09()  { $this->generic_test('_Use.09'); }
}
?>