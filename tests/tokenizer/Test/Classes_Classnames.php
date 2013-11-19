<?php

namespace Test;

include_once(dirname(dirname(__DIR__)).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');

class Classes_Classnames extends Analyzer {
    /* 1 methods */

    public function testClasses_Classnames01()  { $this->generic_test('Classes_Classnames.01'); }
}
?>