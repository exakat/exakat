<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class CastToBoolean extends Analyzer {
    /* 2 methods */

    public function testStructures_CastToBoolean01()  { $this->generic_test('Structures/CastToBoolean.01'); }
    public function testStructures_CastToBoolean02()  { $this->generic_test('Structures/CastToBoolean.02'); }
}
?>