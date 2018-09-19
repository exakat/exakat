<?php

namespace Test\Functions;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class OneLetterFunctions extends Analyzer {
    /* 4 methods */

    public function testFunctions_OneLetterFunctions01()  { $this->generic_test('Functions_OneLetterFunctions.01'); }
    public function testFunctions_OneLetterFunctions02()  { $this->generic_test('Functions_OneLetterFunctions.02'); }
    public function testFunctions_OneLetterFunctions03()  { $this->generic_test('Functions_OneLetterFunctions.03'); }
    public function testFunctions_OneLetterFunctions04()  { $this->generic_test('Functions/OneLetterFunctions.04'); }
}
?>