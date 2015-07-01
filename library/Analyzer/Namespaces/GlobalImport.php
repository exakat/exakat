<?php

namespace Analyzer\Namespaces;

use Analyzer;

class GlobalImport extends Analyzer\Analyzer {
    public function analyze() {
        $classes = $this->loadIni('php_classes.ini', 'classes');
        $classes = array_map('strtolower', $classes);
        
        $this->atomIs('Use')
             ->outIs('USE')
             ->is('originpath', $classes);
        $this->prepareQuery();
    }
}

?>
