<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Instanceof extends Tokenizer {
    /* 11 methods */

    public function test_Instanceof01()  { $this->generic_test('_Instanceof.01'); }
    public function test_Instanceof02()  { $this->generic_test('_Instanceof.02'); }
    public function test_Instanceof03()  { $this->generic_test('_Instanceof.03'); }

    public function test_Instanceof04()  { $this->generic_test('_Instanceof.04'); }
    public function test_Instanceof05()  { $this->generic_test('_Instanceof.05'); }
    public function test_Instanceof06()  { $this->generic_test('_Instanceof.06'); }
    public function test_Instanceof07()  { $this->generic_test('_Instanceof.07'); }
    public function test_Instanceof08()  { $this->generic_test('_Instanceof.08'); }
    public function test_Instanceof09()  { $this->generic_test('_Instanceof.09'); }
    public function test_Instanceof10()  { $this->generic_test('_Instanceof.10'); }
    public function test_Instanceof11()  { $this->generic_test('_Instanceof.11'); }
}
?>