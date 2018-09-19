<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class OneDotOrObjectOperatorPerLine extends Analyzer {
    /* 4 methods */

    public function testStructures_OneDotOrObjectOperatorPerLine01()  { $this->generic_test('Structures/OneDotOrObjectOperatorPerLine.01'); }
    public function testStructures_OneDotOrObjectOperatorPerLine02()  { $this->generic_test('Structures/OneDotOrObjectOperatorPerLine.02'); }
    public function testStructures_OneDotOrObjectOperatorPerLine03()  { $this->generic_test('Structures/OneDotOrObjectOperatorPerLine.03'); }
    public function testStructures_OneDotOrObjectOperatorPerLine04()  { $this->generic_test('Structures/OneDotOrObjectOperatorPerLine.04'); }
}
?>