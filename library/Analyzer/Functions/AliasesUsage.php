<?php

namespace Analyzer\Functions;

use Analyzer;

class AliasesUsage extends Analyzer\Analyzer {
    
    public function analyze() {
        $ini = $this->loadIni('aliases.ini');
        extract($ini);
        $ini = $this->makeFullNsPath(array_keys($ini['alias']));

        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath($ini);
        $this->prepareQuery();
    }
}

?>
