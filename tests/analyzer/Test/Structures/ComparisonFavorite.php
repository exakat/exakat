<?php

namespace Test\Structures;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class ComparisonFavorite extends Analyzer {
    /* 2 methods */

    public function testStructures_ComparisonFavorite01()  { $this->generic_test('Structures/ComparisonFavorite.01'); }
    public function testStructures_ComparisonFavorite02()  { $this->generic_test('Structures/ComparisonFavorite.02'); }
}
?>