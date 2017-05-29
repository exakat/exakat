<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_Globals extends Analyzer {
    /* 9 methods */

    public function testVariables_Globals01()  { $this->generic_test('Variables/Globals.01'); }
    public function testVariables_Globals02()  { $this->generic_test('Variables/Globals.02'); }
    public function testVariables_Globals03()  { $this->generic_test('Variables/Globals.03'); }
    public function testVariables_Globals04()  { $this->generic_test('Variables/Globals.04'); }
    public function testVariables_Globals05()  { $this->generic_test('Variables/Globals.05'); }
    public function testVariables_Globals06()  { $this->generic_test('Variables/Globals.06'); }
    public function testVariables_Globals07()  { $this->generic_test('Variables/Globals.07'); }
    public function testVariables_Globals08()  { $this->generic_test('Variables/Globals.08'); }
    public function testVariables_Globals09()  { $this->generic_test('Variables/Globals.09'); }
}
?>