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

class ExitUsage extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Structures/NoDirectAccess',
                     'Files/IsCliScript');
    }
    
    public function analyze() {
        // while (list($a, $b) = each($c)) {}
        $this->atomIs('Functioncall')
             ->hasNoIn('METHOD')
             ->tokenIs('T_EXIT')
             ->raw('filter{ it.in.loop(1){!(it.object.atom in ["Ifthen", "File"])}{it.object.atom in ["Ifthen", "File"]}.filter{it.in("ANALYZED").has("code", "Analyzer\\\\Structures\\\\NoDirectAccess").any() == false}.any(); }')
             ->goToFile()
             ->analyzerIsNot('Files/IsCliScript')
             ->back('first');
        $this->prepareQuery();
    }
}

?>
