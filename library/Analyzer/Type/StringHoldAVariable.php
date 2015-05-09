<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Analyzer\Type;

use Analyzer;

class StringHoldAVariable extends Analyzer\Analyzer {
    public function analyze() {
        // String that has a PHP variables but ' as delimiters
        $this->atomIs('String')
             ->is('delimiter', "'")
             ->regex('noDelimiter', '[^\\\\\\\\]\\\\\$[a-zA-Z_\\\\x7f-\\\\xff][a-zA-Z0-9_\\\\x7f-\\\\xff]*');
        $this->prepareQuery();

        // variable inside a NOWDOC
        $this->atomIs('Heredoc')
             ->outIs('CONTAINS')
             ->is('nowdoc', true)
             ->outIs('CONCAT')
             ->regex('code', '\\\\\$[a-zA-Z_\\\\x7f-\\\\xff][a-zA-Z0-9_\\\\x7f-\\\\xff]*');
        $this->prepareQuery();

        // <<<NOWDOC NOWDOC (NOWDOC or HEREDOC with wrong syntax)
        $this->atomIs('Heredoc')
             ->tokenIs('T_START_HEREDOC')
             ->savePropertyAs('code', 'd')
             ->outIs('CONTAINS')
             ->outIs('CONCAT')
             ->regex('code', '" + d + "')
             ->inIs('CONCAT');
        $this->prepareQuery();
    }
}

?>
