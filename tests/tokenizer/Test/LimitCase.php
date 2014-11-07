<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class LimitCase extends Tokenizer {
    /* 6 methods */

    public function testLimitCase01()  { $this->generic_test('LimitCase.01'); }
    public function testLimitCase02()  { $this->generic_test('LimitCase.02'); }
    public function testLimitCase03()  { $this->generic_test('LimitCase.03'); }
    public function testLimitCase04()  { $this->generic_test('LimitCase.04'); }
    public function testLimitCase06()  { $this->generic_test('LimitCase.06'); }
}
?>