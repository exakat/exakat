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

namespace Exakat\Analyzer\Constants;

use Exakat\Analyzer\Analyzer;

class CouldBeConstant extends Analyzer {
    public function analyze() {
        // We do that for strings.
        // Not for : Boolean, integers (may be non-trivial ones? ), Floats
        // May be for arrays (sorting issues)
        
        // const A = 'a'; $a = 'a';
        $this->atomIs('Const')
             ->outIs('CONST')
             ->outIs('VALUE')
             ->atomIs(array('String', 'Concatenation', 'Heredoc'))
             ->values('noDelimiter')
             ->unique();
        $stringsConst = $this->rawQuery()->toArray();

        $this->atomIs('Defineconstant')
             ->outIs('VALUE')
             ->atomIs(array('String', 'Concatenation'))
             ->values('noDelimiter')
             ->unique();
        $stringsDefine = $this->rawQuery()->toArray();
        
        $strings = array_merge($stringsConst, $stringsDefine);
        $strings = array_unique($strings);
        
        if (empty($strings)) {
            return;
        }
        
        $this->atomIs(array('String', 'Concatenation', 'Heredoc'))
             ->hasNoParent('Constant', array('VALUE'))
             ->hasNoParent('Defineconstant', array('VALUE'))
             ->hasNoIn('CONCAT')
             ->hasNoOut('CONCAT')
             ->noDelimiterIs($strings)
             ->goToExpression();
        $this->prepareQuery();
    }
}

?>
