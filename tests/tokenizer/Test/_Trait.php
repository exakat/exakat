<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Trait extends Tokenizer {
    /* 7 methods */

    public function test_Trait01()  { $this->generic_test('_Trait.01'); }
    public function test_Trait02()  { $this->generic_test('_Trait.02'); }
    public function test_Trait03()  { $this->generic_test('_Trait.03'); }
    public function test_Trait04()  { $this->generic_test('_Trait.04'); }
    public function test_Trait05()  { $this->generic_test('_Trait.05'); }
    public function test_Trait06()  { $this->generic_test('_Trait.06'); }
    public function test_Trait07()  { $this->generic_test('_Trait.07'); }
}
?>