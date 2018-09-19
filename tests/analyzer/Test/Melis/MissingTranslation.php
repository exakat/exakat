<?php

namespace Test\Melis;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class MissingTranslation extends Analyzer {
    /* 1 methods */

    public function testMelis_MissingTranslation01()  { $this->generic_test('Melis/MissingTranslation.01'); }
}
?>