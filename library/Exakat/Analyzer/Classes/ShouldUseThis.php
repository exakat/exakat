<?php declare(strict_types = 1);
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

class ShouldUseThis extends Analyzer {
    public function dependsOn(): array {
        return array('Complete/OverwrittenMethods',
                     'Classes/UseThis',
                    );
    }

    public function analyze(): void {
        // Non-Static Methods must use $this
        $this->atomIs('Method')
             ->isNot('static', true)
             ->isNot('abstract', true)
             ->not(
                $this->side()
                     ->outIs('BLOCK')
                     ->is('count', 1)
                     ->outIs('EXPRESSION')
                     ->atomIs('Void')
             )
             ->hasClassTrait()
             ->hasNoOut('OVERWRITE')
             ->analyzerIsNot('Classes/UseThis');
        $this->prepareQuery();

        // Static Methods must use a static call to property or variable (not constant though)
        $this->atomIs('Method')
             ->is('static', true)
             ->isNot('abstract', true)
             ->not(
                $this->side()
                     ->outIs('BLOCK')
                     ->is('count', 1)
                     ->outIs('EXPRESSION')
                     ->atomIs('Void')
             )
             ->hasClassTrait()
             ->hasNoOut('OVERWRITE')
             ->analyzerIsNot('Classes/UseThis');
        $this->prepareQuery();
    }
}

?>
