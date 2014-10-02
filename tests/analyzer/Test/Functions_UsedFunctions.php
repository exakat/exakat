<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Functions_UsedFunctions extends Analyzer {
    /* 7 methods */

    public function testFunctions_UsedFunctions01()  { $this->generic_test('Functions_UsedFunctions.01'); }
    public function testFunctions_UsedFunctions02()  { $this->generic_test('Functions_UsedFunctions.02'); }
    public function testFunctions_UsedFunctions03()  { $this->generic_test('Functions_UsedFunctions.03'); }
    public function testFunctions_UsedFunctions04()  { $this->generic_test('Functions_UsedFunctions.04'); }
    public function testFunctions_UsedFunctions05()  { $this->generic_test('Functions_UsedFunctions.05'); }
    public function testFunctions_UsedFunctions06()  { $this->generic_test('Functions_UsedFunctions.06'); }
    public function testFunctions_UsedFunctions07()  { $this->generic_test('Functions_UsedFunctions.07'); }
}
?>