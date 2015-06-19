<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Performances_ArrayMergeInLoops extends Analyzer {
    /* 1 methods */

    public function testPerformances_ArrayMergeInLoops01()  { $this->generic_test('Performances_ArrayMergeInLoops.01'); }
}
?>