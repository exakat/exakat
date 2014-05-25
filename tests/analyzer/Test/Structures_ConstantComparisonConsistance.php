<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_ConstantComparisonConsistance extends Analyzer {
    /* 3 methods */

    public function testStructures_ConstantComparisonConsistance01()  { $this->generic_test('Structures_ConstantComparisonConsistance.01'); }
    public function testStructures_ConstantComparisonConsistance02()  { $this->generic_test('Structures_ConstantComparisonConsistance.02'); }
    public function testStructures_ConstantComparisonConsistance03()  { $this->generic_test('Structures_ConstantComparisonConsistance.03'); }
}
?>