<?php

namespace Test;

include_once(dirname(dirname(dirname(__DIR__))).'/library/Autoload.php');
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class Structures_InconsistentConcatenation extends Analyzer {
    /* 1 methods */

    public function testStructures_InconsistentConcatenation01()  { $this->generic_test('Structures_InconsistentConcatenation.01'); }
}
?>