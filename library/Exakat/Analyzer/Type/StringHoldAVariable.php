<?php
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Type;

use Exakat\Analyzer\Analyzer;

class StringHoldAVariable extends Analyzer {
    public function analyze() {
        $printfs = array('\\printf',
                         '\\vsprintf',
                         '\\sprintf',
                         );

        // String that has a PHP variables but ' as delimiters
        $this->atomIs('String')
             ->hasNoOut('CONCAT')
             // Skip Constant definitions
             ->hasNoParent('Constant', 'VALUE')
             ->hasNoParent(array('Parameter', 'Propertydefinition'), 'DEFAULT')
             // Skip sprintf and one level above
             ->not(
                $this->side()
                     ->inIs('ARGUMENT')
                     ->functioncallIs($printfs)
             )
             ->not(
                $this->side()
                     ->inIs('ARGUMENT')
                     ->inIs('ARGUMENT')
                     ->functioncallIs($printfs)
             )
             ->hasNoParent(array('Constant', 'Parameter', 'Propertydefinition'), 'VALUE')
             ->is('delimiter', "'")
             ->regexIs('noDelimiter', '(?<!%\\\d)(?<!\\\\\\\\)\\\\\$[a-zA-Z_\\\\x7f-\\\\xff][a-zA-Z0-9_\\\\x7f-\\\\xff]*');
        $this->prepareQuery();

        // variable inside a NOWDOC
        $this->atomIs('Heredoc')
             ->isNot('heredoc', true)
             // Skip Constant definitions
             ->hasNoParent('Constant', 'VALUE')
             ->hasNoParent(array('Parameter', 'Propertydefinition'), 'DEFAULT')
             // Skip sprintf and one level above
             ->not(
                $this->side()
                     ->inIs('ARGUMENT')
                     ->functioncallIs($printfs)
             )
             ->not(
                $this->side()
                     ->inIs('ARGUMENT')
                     ->inIs('ARGUMENT')
                     ->functioncallIs($printfs)
             )             ->outIs('CONCAT')
             ->regexIs('fullcode', '[^%]?[^\\\\\\\\]?\\\\\$[a-zA-Z_\\\\x7f-\\\\xff][a-zA-Z0-9_\\\\x7f-\\\\xff]+');
        $this->prepareQuery();

        // <<<NOWDOC NOWDOC (NOWDOC or HEREDOC with wrong syntax)
        $this->atomIs('Heredoc')
             ->savePropertyAs('delimiter', 'd')
             ->outIs('CONCAT')
             ->regexIs('fullcode', '\\\\b" + d + "\\\\b')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
