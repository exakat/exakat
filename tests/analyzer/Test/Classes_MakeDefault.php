<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Classes_MakeDefault extends Analyzer {
    /* 1 methods */

    public function testClasses_MakeDefault01()  { $this->generic_test('Classes_MakeDefault.01'); }
}
?>