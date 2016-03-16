<?php

namespace Analyzer\Php;

use Analyzer;

class SetExceptionHandlerPHP7 extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Functions/MarkCallable');
    }
    
    public function analyze() {
        // With function name in a string
        $this->atomFunctionIs('\set_exception_handler')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->regexNot('noDelimiter', '::')
             ->analyzerIs('Functions/MarkCallable')
             ->functionDefinition()
             ->inIs('NAME')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('CLASS')
             ->fullnspathIsNot('\\Throwable')
             ->back('first');
        $this->prepareQuery();

        // With class:method name in a string
        $this->atomFunctionIs('\set_exception_handler')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->regex('noDelimiter', '::')
             ->raw('sideEffect{ methode = it.cbMethod }')
             ->analyzerIs('Functions/MarkCallable')
             ->classDefinition()
             ->outIs('BLOCK')
             ->outIs('ELEMENT')
             ->atomIs('Function')
             ->outIs('NAME')
             ->samePropertyAs('code', 'methode')
             ->inIs('NAME')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('CLASS')
             ->fullnspathIsNot('\\Throwable')
             ->back('first');
        $this->prepareQuery();

        // With parent:method name in a string

        // With closure
        $this->atomFunctionIs('\set_exception_handler')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('Function')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->atomIs('Typehint')
             ->outIs('CLASS')
             ->fullnspathIsNot('\\Throwable')
             ->back('first');
        $this->prepareQuery();

        // With array (class + method)
        $this->atomFunctionIs('\set_exception_handler')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('Functioncall')
             ->tokenIs(array('T_ARRAY', 'T_OPEN_BRACKET'))
             ->outIs('ARGUMENTS')
             ->analyzerIs('Functions/MarkCallable')
             ->inIs('ARGUMENTS')
             ->raw('sideEffect{ if (it.out("ARGUMENTS").out("ARGUMENT").has("rank", 1).has("atom", "String").any()) {
                                     methode = it.out("ARGUMENTS").out("ARGUMENT").has("rank", 1).next().noDelimiter; 
                                }
                            }')
             ->filter('it.out("ARGUMENTS").out("ARGUMENT").has("rank", 0).has("atom", "String").any()') 
             ->raw('transform{ f = it.out("ARGUMENTS").out("ARGUMENT").has("rank", 0).next().noDelimiter; 
                               if (f.substring(0, 1) != "\\\\") { f = "\\\\" + f; }
                                it.filter{ g.idx("classes").get("path", f).any(); }
                                 .transform{ g.idx("classes")[["path": f]].next(); }
                                 .next();
                              }')
              ->outIs('BLOCK')
              ->outIs('ELEMENT')
              ->atomIs('Function')
              ->outIs('NAME')
              ->samePropertyAs('code', 'methode')
              ->inIs('NAME')
              ->outIs('ARGUMENTS')
              ->outIs('ARGUMENT')
              ->atomIs('Typehint')
              ->outIs('CLASS')
              ->fullnspathIsNot('\\Throwable')
              ->back('first');
        $this->prepareQuery();

        // With array (object + method)
    }
}

?>
