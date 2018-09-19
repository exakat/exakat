<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ImplementedMethodsArePublic extends Analyzer {
    /* 4 methods */

    public function testClasses_ImplementedMethodsArePublic01()  { $this->generic_test('Classes/ImplementedMethodsArePublic.01'); }
    public function testClasses_ImplementedMethodsArePublic02()  { $this->generic_test('Classes/ImplementedMethodsArePublic.02'); }
    public function testClasses_ImplementedMethodsArePublic03()  { $this->generic_test('Classes/ImplementedMethodsArePublic.03'); }
    public function testClasses_ImplementedMethodsArePublic04()  { $this->generic_test('Classes/ImplementedMethodsArePublic.04'); }
}
?>