<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Shell extends Tokenizer {
    /* 14 methods */

    public function testShell01()  { $this->generic_test('Shell.01'); }
    public function testShell02()  { $this->generic_test('Shell.02'); }
    public function testShell03()  { $this->generic_test('Shell.03'); }
    public function testShell04()  { $this->generic_test('Shell.04'); }
    public function testShell05()  { $this->generic_test('Shell.05'); }
    public function testShell06()  { $this->generic_test('Shell.06'); }
    public function testShell07()  { $this->generic_test('Shell.07'); }
    public function testShell08()  { $this->generic_test('Shell.08'); }
    public function testShell09()  { $this->generic_test('Shell.09'); }
    public function testShell10()  { $this->generic_test('Shell.10'); }
    public function testShell11()  { $this->generic_test('Shell.11'); }
    public function testShell12()  { $this->generic_test('Shell.12'); }
    public function testShell13()  { $this->generic_test('Shell.13'); }
    public function testShell14()  { $this->generic_test('Shell.14'); }
}
?>