<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class _Foreach extends Tokenizeur {
    /* 12 methods */

    public function test_Foreach01()  { $this->generic_test('_Foreach.01'); }
    public function test_Foreach02()  { $this->generic_test('_Foreach.02'); }
    public function test_Foreach03()  { $this->generic_test('_Foreach.03'); }
    public function test_Foreach04()  { $this->generic_test('_Foreach.04'); }
    public function test_Foreach05()  { $this->generic_test('_Foreach.05'); }
    public function test_Foreach06()  { $this->generic_test('_Foreach.06'); }
    public function test_Foreach07()  { $this->generic_test('_Foreach.07'); }
    public function test_Foreach08()  { $this->generic_test('_Foreach.08'); }
    public function test_Foreach09()  { $this->generic_test('_Foreach.09'); }
    public function test_Foreach10()  { $this->generic_test('_Foreach.10'); }
    public function test_Foreach11()  { $this->generic_test('_Foreach.11'); }
    public function test_Foreach12()  { $this->generic_test('_Foreach.12'); }
}
?>