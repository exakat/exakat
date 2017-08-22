<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_HasMagicProperty extends Analyzer {
    /* 3 methods */

    public function testClasses_HasMagicProperty01()  { $this->generic_test('Classes_HasMagicProperty.01'); }
    public function testClasses_HasMagicProperty02()  { $this->generic_test('Classes/HasMagicProperty.02'); }
    public function testClasses_HasMagicProperty03()  { $this->generic_test('Classes/HasMagicProperty.03'); }
}
?>