<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_MismatchTypeAndDefault extends Analyzer {
    /* 6 methods */

    public function testFunctions_MismatchTypeAndDefault01()  { $this->generic_test('Functions/MismatchTypeAndDefault.01'); }
    public function testFunctions_MismatchTypeAndDefault02()  { $this->generic_test('Functions/MismatchTypeAndDefault.02'); }
    public function testFunctions_MismatchTypeAndDefault03()  { $this->generic_test('Functions/MismatchTypeAndDefault.03'); }
    public function testFunctions_MismatchTypeAndDefault04()  { $this->generic_test('Functions/MismatchTypeAndDefault.04'); }
    public function testFunctions_MismatchTypeAndDefault05()  { $this->generic_test('Functions/MismatchTypeAndDefault.05'); }
    public function testFunctions_MismatchTypeAndDefault06()  { $this->generic_test('Functions/MismatchTypeAndDefault.06'); }
}
?>