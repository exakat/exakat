<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_UseClassOperator extends Analyzer {
    /* 3 methods */

    public function testClasses_UseClassOperator01()  { $this->generic_test('Classes/UseClassOperator.01'); }
    public function testClasses_UseClassOperator02()  { $this->generic_test('Classes/UseClassOperator.02'); }
    public function testClasses_UseClassOperator03()  { $this->generic_test('Classes/UseClassOperator.03'); }
}
?>