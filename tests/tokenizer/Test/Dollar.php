<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Dollar extends Tokenizer {
    /* 8 methods */

    public function testDollar01()  { $this->generic_test('Dollar.01'); }
    public function testDollar02()  { $this->generic_test('Dollar.02'); }
    public function testDollar03()  { $this->generic_test('Dollar.03'); }
    public function testDollar04()  { $this->generic_test('Dollar.04'); }
    public function testDollar05()  { $this->generic_test('Dollar.05'); }
    public function testDollar06()  { $this->generic_test('Dollar.06'); }
    public function testDollar07()  { $this->generic_test('Dollar.07'); }
    public function testDollar08()  { $this->generic_test('Dollar.08'); }
}
?>