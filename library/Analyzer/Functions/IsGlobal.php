<?php

namespace Analyzer\Functions;

use Analyzer;

class IsGlobal extends Analyzer\Analyzer {

    public function analyze() {
        $this->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->hasNoIn('METHOD')
             ->raw('filter{it.in.loop(1){ it.object.atom != "File"}{ it.object.atom in ["Class", "Trait", "Function"] }.any() == false}');
        $this->prepareQuery();
    }
}

?>
