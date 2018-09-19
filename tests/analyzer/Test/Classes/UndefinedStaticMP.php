<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class UndefinedStaticMP extends Analyzer {
    /* 3 methods */

    public function testClasses_UndefinedStaticMP01()  { $this->generic_test('Classes_UndefinedStaticMP.01'); }
    public function testClasses_UndefinedStaticMP02()  { $this->generic_test('Classes_UndefinedStaticMP.02'); }
    public function testClasses_UndefinedStaticMP03()  { $this->generic_test('Classes/UndefinedStaticMP.03'); }
}
?>