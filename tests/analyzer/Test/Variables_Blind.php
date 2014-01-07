<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Variables_Blind extends Analyzer {
    /* 3 methods */

    public function testVariables_Blind01()  { $this->generic_test('Variables_Blind.01'); }
    public function testVariables_Blind02()  { $this->generic_test('Variables_Blind.02'); }
    public function testVariables_Blind03()  { $this->generic_test('Variables_Blind.03'); }
}
?>