<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Analyzer\Structures;

use Analyzer;

class NoHardcodedIp extends Analyzer\Analyzer {
    public function analyze() {
        // a string that fits the description of an IP
        $this->atomIs('String')
             ->noDelimiterIsNot('127.0.0.1')
             ->regex('noDelimiter', '^(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\\\\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)(:\\\\d+)?\\$');
        $this->prepareQuery();
        
        // a string that looks like a domain name. 
        $this->atomIs('String')
             ->regex('noDelimiter', '^((?!-)[A-Za-z0-9-]{1,63}(?<!-)\\\\.)+[A-Za-z]{2,6}\\$');
        $this->prepareQuery();
        
        $hosts = $this->loadJson('php_remote_access.json');
        foreach($hosts as $position => $functions) {
            $this->atomFunctionIs($functions)
                 ->outIs('ARGUMENTS')
                 ->outIs('ARGUMENT')
                 ->is('rank', (int) $position)
                 ->atomIs(array('Identifier', 'Nsname'))
                 ->hasConstantDefinition()
                 ->back('first');
            $this->prepareQuery();
        }
    }
}

?>
