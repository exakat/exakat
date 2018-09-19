<?php

namespace Test\Portability;

use Test\Analyzer;

include_once dirname(__DIR__, 4).'/library/Autoload.php';
spl_autoload_register('Autoload::autoload_test');
spl_autoload_register('Autoload::autoload_phpunit');
spl_autoload_register('Autoload::autoload_library');

class LinuxOnlyFiles extends Analyzer {
    /* 1 methods */

    public function testPortability_LinuxOnlyFiles01()  { $this->generic_test('Portability/LinuxOnlyFiles.01'); }
}
?>