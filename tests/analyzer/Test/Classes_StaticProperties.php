<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Classes_StaticProperties extends Analyzer {
    /* 4 methods */

    public function testClasses_StaticProperties01()  { $this->generic_test('Classes_StaticProperties.01'); }
    public function testClasses_StaticProperties02()  { $this->generic_test('Classes_StaticProperties.02'); }
    public function testClasses_StaticProperties03()  { $this->generic_test('Classes_StaticProperties.03'); }
    public function testClasses_StaticProperties04()  { $this->generic_test('Classes_StaticProperties.04'); }
}
?>