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


namespace Exakat\Analyzer\Classes;

use Exakat\Analyzer\Analyzer;

class UselessAbstract extends Analyzer {
    public function dependsOn() {
        return array('Classes/OnlyStaticMethods',
                    );
    }
    
    public function analyze() {
        // abstract class that are never instanciated
        $this->atomIs('Class')
             ->is('abstract', true)
             ->analyzerIsNot('Classes/OnlyStaticMethods')
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs('DEFINITION')
                             ->atomIsNot('This')
                     )
             );
        $this->prepareQuery();

        // abstract class without nothing in
        $this->atomIs('Class')
             ->is('abstract', true)
             ->filter(
                $this->side()
                     ->outIs('DEFINITION')
                     ->atomIsNot('This')
             )
             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs(self::$CLASS_ELEMENTS)
                             ->atomIsNot('Virtualproperty')
                     )
             );
        $this->prepareQuery();

        // abstract class with not methods nor const nor trait
        $this->atomIs('Class')
             ->is('abstract', true)
             ->filter(
                $this->side()
                     ->outIs('DEFINITION')
                     ->atomIsNot('This')
             )

             ->not(
                $this->side()
                     ->filter(
                        $this->side()
                             ->outIs(self::$CLASS_ELEMENTS)
                             ->atomIsNot('Virtualproperty')
                     )
             );
        $this->prepareQuery();
     }
}

?>
