<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Variables_OverwrittenLiterals extends Analyzer {
    /* 3 methods */

    public function testVariables_OverwrittenLiterals01()  { $this->generic_test('Variables_OverwrittenLiterals.01'); }
    public function testVariables_OverwrittenLiterals02()  { $this->generic_test('Variables_OverwrittenLiterals.02'); }
    public function testVariables_OverwrittenLiterals03()  { $this->generic_test('Variables_OverwrittenLiterals.03'); }
}
?>