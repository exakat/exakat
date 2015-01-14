<?php

namespace Analyzer\Functions;

use Analyzer;

class UsesDefaultArguments extends Analyzer\Analyzer {
    public function analyze() {
        $data = new \Data\Methods();
        $functions = $data->getFunctionsArgsInterval();

        $positions = array(array(), array(), array(), array(), array());
        foreach($functions as $function) {
            if ($function['args_min'] == $function['args_max']) { continue; }
            if ($function['args_max'] == 100) { continue; }
            for ($i = $function['args_min'] + 1; $i <= $function['args_max']; $i++) {
                $positions[$i - 1][] = '\\'.$function['name'];
            }
        }
        
        foreach($positions as $position => $f) {
            $this->atomIs('Functioncall')
                 ->hasNoIn('METHOD')
                 ->tokenIs(array('T_STRING','T_NS_SEPARATOR'))
                 ->fullnspath($f)
                 ->outIs('ARGUMENTS')
                 ->noChildWithRank('ARGUMENT', $position)
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
