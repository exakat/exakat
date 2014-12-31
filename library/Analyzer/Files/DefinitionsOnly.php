<?php

namespace Analyzer\Files;

use Analyzer;

class DefinitionsOnly extends Analyzer\Analyzer {
    public static $definitions = array('Interface', 'Trait', 'Function', 'Const', 'Class', 'Namespace', 'Use', 'Global');
    
    public function dependsOn() {
        return array('Structures/NoDirectAccess');
    }
    
    public function analyze() {
        $definitions = '"'.join('", "', self::$definitions).'"';
        $definitions = 'it.atom in ['.$definitions.'] || (it.atom == "Functioncall" && it.fullnspath == "\\\\define") || it.in("ANALYZED").has("code", "Analyzer\\\\Structures\\\\NoDirectAccess").any()';
//        $definitions = 'it.atom in ['.$definitions.'] || (it.atom == "Functioncall" && it.fullnspath == "\\\\define")';
        
        $this->atomIs("File")
             ->outIs('FILE')
             ->atomIs('Phpcode')
             ->outIs('CODE')
             // spot a definition
             ->raw('filter{ it.out("ELEMENT").filter{ '.$definitions.' }.any()}')
             // spot a non-definition
             ->raw('filter{ it.out("ELEMENT").filter{ !('.$definitions.')}.any() == false}')
             ->back('first')
;
//        $this->printQuery();
        $this->prepareQuery();
    }
}

?>
