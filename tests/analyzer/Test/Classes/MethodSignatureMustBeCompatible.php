<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class MethodSignatureMustBeCompatible extends Analyzer {
    /* 2 methods */

    public function testClasses_MethodSignatureMustBeCompatible01()  { $this->generic_test('Classes/MethodSignatureMustBeCompatible.01'); }
    public function testClasses_MethodSignatureMustBeCompatible02()  { $this->generic_test('Classes/MethodSignatureMustBeCompatible.02'); }
}
?>