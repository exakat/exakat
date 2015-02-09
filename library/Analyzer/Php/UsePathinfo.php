<?php

namespace Analyzer\Php;

use Analyzer;

class UsePathinfo extends Analyzer\Analyzer {
    public function analyze() {
        // getting the file extension with explode
        /*
        $temp = explode('.', $config);
		$ext = array_pop($temp);
        */
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->hasNoIn('METHOD')
             ->fullnspath(array('\\explode', '\\split'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->noDelimiter('.')
             ->back('first')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'tmpvar')
             ->inIs('LEFT')
             ->nextSibling()
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->hasNoIn('METHOD')
             ->fullnspath('\\array_pop')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('code', 'tmpvar')
             ->back('first');
        $this->prepareQuery();

        /*
		$exploded = explode('.', $filename);
		
		if (count($exploded) > 1) {
			$extension = array_pop($exploded);
		}
		*/
        $this->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->hasNoIn('METHOD')
             ->fullnspath(array('\\explode', '\\split'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->noDelimiter('.')
             ->back('first')
             ->outIs('LEFT')
             ->savePropertyAs('code', 'tmpvar')
             ->inIs('LEFT')
             ->nextSibling()
             ->atomIs('Ifthen')
             ->outIs('THEN')
             ->outIs('ELEMENT')
             ->atomIs('Assignation')
             ->outIs('RIGHT')
             ->atomIs('Functioncall')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->hasNoIn('METHOD')
             ->fullnspath('\\array_pop')
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->samePropertyAs('code', 'tmpvar')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
