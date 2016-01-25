<?php

namespace Analyzer\Files;

use Analyzer;

class IsComponent extends Analyzer\Analyzer {
    /* Remove this if useless
    public function dependsOn() {
        return array('MethodDefinition');
    }
    */
    
    public function analyze() {
        $this->atomIs('File')
             ->outIs('FILE')
             ->outIs('CODE')
             ->raw('transform{ if( it.out("ELEMENT").has("atom", "Namespace").out("BLOCK").any()) {it.out("ELEMENT").out("BLOCK").next();} else {it;}}')
             ->filter(' it.out("ELEMENT")
                .filter{!(it.atom in ["Use", "Class", "Interface", "Trait", "Include", "Global", "Const", "Visibility"])}
                .filter{ it.atom != "Function"     || it.out("NAME").hasNot("code", "").any() == false } // Function but not closure
                .filter{ it.atom != "Functioncall" || it.has("fullnspath", "\\\\define").any() == false} // Functioncall but define

                .filter{ it.atom != "Ifthen"       || it.out("THEN")
                                                        .out("ELEMENT").filter{!(it.atom in ["Use", "Class", "Interface", "Trait", "Include", "Global", "Visibility"])} // No Const
                                                                       .filter{ it.atom != "Function"     || it.out("NAME").hasNot("code", "").any() == false} // Function but not closure
                                                                       .filter{ it.atom != "Functioncall" || it.has("fullnspath", "\\\\define").any() == false} // Functioncall but define
                                                                       .any()} // Functioncall but define

                .any() == false')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
