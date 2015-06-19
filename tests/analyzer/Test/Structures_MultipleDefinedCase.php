<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_MultipleDefinedCase extends Analyzer {
    /* 3 methods */

    public function testStructures_MultipleDefinedCase01()  { $this->generic_test('Structures_MultipleDefinedCase.01'); }
    public function testStructures_MultipleDefinedCase02()  { $this->generic_test('Structures_MultipleDefinedCase.02'); }
    public function testStructures_MultipleDefinedCase03()  { $this->generic_test('Structures_MultipleDefinedCase.03'); }
}
?>