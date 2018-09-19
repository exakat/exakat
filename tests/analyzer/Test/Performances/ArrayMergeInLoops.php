<?php

namespace Test\Performances;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ArrayMergeInLoops extends Analyzer {
    /* 4 methods */

    public function testPerformances_ArrayMergeInLoops01()  { $this->generic_test('Performances_ArrayMergeInLoops.01'); }
    public function testPerformances_ArrayMergeInLoops02()  { $this->generic_test('Performances/ArrayMergeInLoops.02'); }
    public function testPerformances_ArrayMergeInLoops03()  { $this->generic_test('Performances/ArrayMergeInLoops.03'); }
    public function testPerformances_ArrayMergeInLoops04()  { $this->generic_test('Performances/ArrayMergeInLoops.04'); }
}
?>