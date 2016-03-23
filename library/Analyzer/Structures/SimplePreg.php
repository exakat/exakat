<?php

namespace Analyzer\Structures;

use Analyzer;

class SimplePreg extends Analyzer\Analyzer {
    public function analyze() {
        // almost data/pcre.ini but not preg_last_error
        $functions = array('\preg_match', '\preg_match_all', '\preg_replace', '\preg_replace_callback', 
                           '\preg_filter', '\preg_split', '\preg_quote', '\preg_grep');

        $this->atomFunctionIs($functions)
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String')
             // Normal delimiters
             ->regexNot('noDelimiter', '(?<!\\\\\\\\)[.?*+\\\\\$\\\\^|{}()\\\\[\\\\]|]')
             // Simple assertions
             ->regexNot('noDelimiter', '\\\\\\\\[bBAZz]')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
