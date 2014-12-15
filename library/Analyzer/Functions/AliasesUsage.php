<?php

namespace Analyzer\Functions;

use Analyzer;

class AliasesUsage extends Analyzer\Analyzer {
    
    public function analyze() {
        $ini = $this->loadIni('aliases.ini');
        extract($ini);

        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD')
             ->code(array_keys($ini['alias']));
             
        $this->prepareQuery();
    }
}

?>
