<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_WrittenOnlyVariable extends Analyzer {
    /* 6 methods */

    public function testVariables_WrittenOnlyVariable01()  { $this->generic_test('Variables_WrittenOnlyVariable.01'); }
    public function testVariables_WrittenOnlyVariable02()  { $this->generic_test('Variables_WrittenOnlyVariable.02'); }
    public function testVariables_WrittenOnlyVariable03()  { $this->generic_test('Variables_WrittenOnlyVariable.03'); }
    public function testVariables_WrittenOnlyVariable04()  { $this->generic_test('Variables_WrittenOnlyVariable.04'); }
    public function testVariables_WrittenOnlyVariable05()  { $this->generic_test('Variables/WrittenOnlyVariable.05'); }
    public function testVariables_WrittenOnlyVariable06()  { $this->generic_test('Variables/WrittenOnlyVariable.06'); }
}
?>