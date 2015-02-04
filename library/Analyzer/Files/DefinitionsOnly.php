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
        
        // all cases without extra string before/after the script
        
        // one or several namespaces
        $this->atomIs('File')
             ->outIs('FILE')
             ->atomIs('Phpcode')
             ->outIs('CODE')
//             ->outIs('ELEMENT')
//             ->atomIs('Namespace')
//             ->outIs('BLOCK')

             ->raw('filter{ it.out("ELEMENT").has("atom", "Namespace").out("BLOCK").out.loop(1){!(it.object.atom in ['.$definitionsList.'])}{!(it.object.atom in ['.$definitionsList.'])}.any() == false}')

             // first level of the code

             // spot a definition
             ->raw('filter{ it.out("ELEMENT").has("atom", "Namespace").out("BLOCK").out("ELEMENT").filter{ '.$definitions.' }.any()}')

             // spot a non-definition
             ->raw('filter{ it.out("ELEMENT").has("atom", "Namespace").out("BLOCK").out("ELEMENT").filter{ !('.$definitions.')}.any() == false}')

             ->back('first');
        $this->prepareQuery();

        // namespaces are implicit
        $this->atomIs('File')
             ->outIs('FILE')
             ->atomIs('Phpcode')
             ->outIs('CODE')
             
             ->raw('filter{ it.out("ELEMENT").has("atom", "Namespace").any() == false}')

             ->raw('filter{ it.out.loop(1){!(it.object.atom in ['.$definitionsList.'])}{!(it.object.atom in ['.$definitionsList.'])}.any() == false}')

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
