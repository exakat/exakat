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


namespace Analyzer\Constants;

use Analyzer;

class ConstantUsage extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Extensions\\Extstandard');
    }
    
    public function analyze() {
        // Nsname that is not used somewhere else
        $this->atomIs('Nsname')
             ->hasNoIn(array('NEW', 'SUBNAME', 'USE', 'NAME', 'NAMESPACE', 'EXTENDS', 'IMPLEMENTS', 'CLASS'));
        $this->prepareQuery();

        // Identifier that is not used somewhere else
        $this->atomIs('Identifier')
             ->codeIsNot(array('true', 'false', 'null'))
             ->hasNoIn(array('NEW', 'SUBNAME', 'USE', 'NAME', 'NAMESPACE', 'CONSTANT', 'PROPERTY', 'CLASS', 'EXTENDS', 'IMPLEMENTS', 'CLASS', 'AS'));
        $this->prepareQuery();

        // special case for Boolean and Null
        $this->atomIs(array('Boolean', 'Null'));
        $this->prepareQuery();
        
        // defined('constant') : then the string is a constant
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->fullnspath(array('\\defined', '\\constant'))
             ->outIs('ARGUMENTS')
             ->outIs('ARGUMENT')
             ->is('rank', 0)
             ->atomIs('String');
        $this->prepareQuery();
    }
}

?>
