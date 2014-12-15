<?php

namespace Analyzer\Constants;

use Analyzer;

class ConstRecommended extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Constants\\ConstantUsage');
    }
    
    public function analyze() {
        // define('const', literal);
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD') // possibly new too
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\define')
             ->outIs('ARGUMENTS')
             ->_as('args')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->back('args')
             ->outIs('ARGUMENT')
             ->is('rank', 1)
             ->atomIs(array('String', 'Float', 'Integer', 'Boolean', 'Null', 'Staticconstant'))
             ->hasNoOut('CONTAIN')
             ->back('first');
        $this->prepareQuery();

        // define('const', other constant);
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD') // possibly new too
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\define')
             ->outIs('ARGUMENTS')
             ->_as('args')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->back('args')
             ->outIs('ARGUMENT')
             ->is('rank', 1)
             ->atomIs(array('Identifier', 'Nsname'))
             ->analyzerIs('Analyzer\\Constants\\ConstantUsage')
             ->back('first');
        $this->prepareQuery();

        // define('const', expression);
        $this->atomIs("Functioncall")
             ->hasNoIn('METHOD') // possibly new too
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath('\\define')
             ->outIs('ARGUMENTS')
             ->_as('args')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             ->back('args')
             ->outIs('ARGUMENT')
             ->is('rank', 1)
             ->atomIsNot(array('Identifier', 'Nsname','String', 'Float', 'Integer', 'Boolean', 'Null', 'Staticconstant', 'Variable'))
             ->raw('filter{ it.out.loop(1){true}{true}.filter{it.atom in ["Variable", "Functioncall"]}.any() == false}')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
