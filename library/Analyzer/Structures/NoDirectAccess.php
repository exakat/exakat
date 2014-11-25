<?php

namespace Analyzer\Structures;

use Analyzer;

class NoDirectAccess extends Analyzer\Analyzer {
    public function analyze() {
        //defined('AJXP_EXEC') or die('Access not allowed'); : Constant used!
        $this->atomIs('Logical')
             ->code('or')
//             ->outIs('RIGHT')
             // find !defined and defined
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_STRING')
             ->fullnspath('\\defined')
             ->back('first')
//             ->outIs('LEFT')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_EXIT', 'T_DIE'))
             ->fullnspath(array('\\die', '\\exit'))
             ->back('first');
//        $this->printQuery();
        $this->prepareQuery();

        //if(!defined('CMS'))die/exit 
        $this->atomIs('Ifthen')
             ->outIs('CONDITION')
             // find !defined and defined
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_STRING')
             ->fullnspath('\\defined')
             ->back('first')
             ->outIs('THEN')
             ->atomInside('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_EXIT', 'T_DIE'))
             ->fullnspath(array('\\die', '\\exit'))
             ->back('first');
        $this->prepareQuery();
    }
}

?>