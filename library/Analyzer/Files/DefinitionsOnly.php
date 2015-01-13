<?php

namespace Analyzer\Files;

use Analyzer;

class DefinitionsOnly extends Analyzer\Analyzer {
    public static $definitions = array('Interface', 'Trait', 'Function', 'Const', 'Class', 'Use', 'Global');
    //'Namespace',  is excluded
    
    public function dependsOn() {
        return array('Structures/NoDirectAccess');
    }
    
    public function analyze() {
        $definitionsList = '"'.implode('", "', self::$definitions).'"';
        $definitions = 'it.atom in ['.$definitionsList.', "Namespace"] || (it.atom == "Functioncall" && !(it.fullnspath in ["\\\\define", "\\\\set_session_handler", "\\\\set_error_handler"])) || it.in("ANALYZED").has("code", "Analyzer\\\\Structures\\\\NoDirectAccess").any()';
        
        $this->atomIs('File')
             ->outIs('FILE')
             ->atomIs('Phpcode')
             ->outIs('CODE')
             
             ->raw('out.loop(1){!(it.object.atom in ['.$definitionsList.'])}{!(it.object.atom in ['.$definitionsList.'])}')

             // first level of the code

             // spot a definition
             ->raw('filter{ it.out("ELEMENT").filter{ '.$definitions.' }.any()}')
             // spot a non-definition
             ->raw('filter{ it.out("ELEMENT").filter{ !('.$definitions.')}.any() == false}')

             ->back('first');
        $this->prepareQuery();
    }
}

?>
