<?php

namespace Test\Classes;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class MethodUsedBelow extends Analyzer {
    /* 4 methods */

    public function testClasses_MethodUsedBelow01()  { $this->generic_test('Classes/MethodUsedBelow.01'); }
    public function testClasses_MethodUsedBelow02()  { $this->generic_test('Classes/MethodUsedBelow.02'); }
    public function testClasses_MethodUsedBelow03()  { $this->generic_test('Classes/MethodUsedBelow.03'); }
    public function testClasses_MethodUsedBelow04()  { $this->generic_test('Classes/MethodUsedBelow.04'); }
}
?>