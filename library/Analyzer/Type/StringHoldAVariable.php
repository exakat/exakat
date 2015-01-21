<?php

namespace Analyzer\Type;

use Analyzer;

class StringHoldAVariable extends Analyzer\Analyzer {
    public function analyze() {
        $this->atomIs('String')
             ->is('delimiter', "'")
             ->regex('noDelimiter', '[^\\\\\\\\]\\\\\$[a-zA-Z_\\\\x7f-\\\\xff][a-zA-Z0-9_\\\\x7f-\\\\xff]*');
        $this->prepareQuery();

        // variable inside a NOWDOC
        $this->atomIs('String')
             ->is('nowdoc', 'true')
             ->outIs('CONTAIN')
             ->outIs('CONCAT')
             ->regex('code', '\\\\\$[a-zA-Z_\\\\x7f-\\\\xff][a-zA-Z0-9_\\\\x7f-\\\\xff]*');
        $this->prepareQuery();

        // variable inside a NOWDOC
        $this->atomIs('String')
             ->tokenIs('T_START_HEREDOC')
             ->savePropertyAs('code', 'd')
             ->outIs('CONTAIN')
             ->outIs('CONCAT')
             ->regex('code', '" + d + "')
             ->inIs('CONCAT');
        $this->prepareQuery();
    }
}

?>
