<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Constants_InconsistantCase extends Analyzer {
    /* 7 methods */

    public function testConstants_InconsistantCase01()  { $this->generic_test('Constants_InconsistantCase.01'); }
    public function testConstants_InconsistantCase02()  { $this->generic_test('Constants_InconsistantCase.02'); }
    public function testConstants_InconsistantCase03()  { $this->generic_test('Constants_InconsistantCase.03'); }
    public function testConstants_InconsistantCase04()  { $this->generic_test('Constants_InconsistantCase.04'); }
    public function testConstants_InconsistantCase05()  { $this->generic_test('Constants_InconsistantCase.05'); }
    public function testConstants_InconsistantCase06()  { $this->generic_test('Constants/InconsistantCase.06'); }
    public function testConstants_InconsistantCase07()  { $this->generic_test('Constants/InconsistantCase.07'); }
}
?>