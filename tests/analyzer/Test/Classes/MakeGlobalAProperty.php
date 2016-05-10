<?php

namespace Test;

include_once(dirname(dirname(dirname(dirname(__DIR__)))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_MakeGlobalAProperty extends Analyzer {
    /* 1 methods */

    public function testClasses_MakeGlobalAProperty01()  { $this->generic_test('Classes/MakeGlobalAProperty.01'); }
}
?>