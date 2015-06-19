<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Abstract extends Tokenizer {
    /* 5 methods */

    public function test_Abstract01()  { $this->generic_test('_Abstract.01'); }
    public function test_Abstract02()  { $this->generic_test('_Abstract.02'); }
    public function test_Abstract03()  { $this->generic_test('_Abstract.03'); }
    public function test_Abstract04()  { $this->generic_test('_Abstract.04'); }
    public function test_Abstract05()  { $this->generic_test('_Abstract.05'); }
}
?>