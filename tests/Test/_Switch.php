<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Switch extends Tokenizeur {
    /* 9 methods */

    public function test_Switch01()  { $this->generic_test('_Switch.01'); }
    public function test_Switch02()  { $this->generic_test('_Switch.02'); }
    public function test_Switch03()  { $this->generic_test('_Switch.03'); }
    public function test_Switch04()  { $this->generic_test('_Switch.04'); }
    public function test_Switch05()  { $this->generic_test('_Switch.05'); }
    public function test_Switch06()  { $this->generic_test('_Switch.06'); }
    public function test_Switch07()  { $this->generic_test('_Switch.07'); }
    public function test_Switch08()  { $this->generic_test('_Switch.08'); }
    public function test_Switch09()  { $this->generic_test('_Switch.09'); }
}
?>