<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_EmptyFunction extends Analyzer {
    /* 8 methods */

    public function testFunctions_EmptyFunction01()  { $this->generic_test('Functions_EmptyFunction.01'); }
    public function testFunctions_EmptyFunction02()  { $this->generic_test('Functions_EmptyFunction.02'); }
    public function testFunctions_EmptyFunction03()  { $this->generic_test('Functions_EmptyFunction.03'); }
    public function testFunctions_EmptyFunction04()  { $this->generic_test('Functions/EmptyFunction.04'); }
    public function testFunctions_EmptyFunction05()  { $this->generic_test('Functions/EmptyFunction.05'); }
    public function testFunctions_EmptyFunction06()  { $this->generic_test('Functions/EmptyFunction.06'); }
    public function testFunctions_EmptyFunction07()  { $this->generic_test('Functions/EmptyFunction.07'); }
    public function testFunctions_EmptyFunction08()  { $this->generic_test('Functions/EmptyFunction.08'); }
}
?>