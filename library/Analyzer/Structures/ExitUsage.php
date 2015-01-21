<?php

namespace Analyzer\Structures;

use Analyzer;

class ExitUsage extends Analyzer\Analyzer {
    public function dependsOn() {
        return array("Analyzer\\Structures\\NoDirectAccess");
    }
    
    public function analyze() {
        // while (list($a, $b) = each($c)) {}
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_DIE', 'T_EXIT'))
             ->raw('filter{ it.in.loop(1){!(it.object.atom in ["Ifthen", "File"])}{it.object.atom in ["Ifthen", "File"]}.filter{it.in("ANALYZED").has("code", "Analyzer\\\\Structures\\\\NoDirectAccess").any() == false}.any(); }')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
