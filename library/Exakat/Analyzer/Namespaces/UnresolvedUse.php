<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Namespaces;

use Exakat\Analyzer\Analyzer;

class UnresolvedUse extends Analyzer {
    public function dependsOn() {
        return array('Classes/IsExtClass',
                     'Interfaces/IsExtInterface',
                     'Traits/IsExtTrait',
                     'Composer/IsComposerNsname',
                    );
    }

    public function analyze() {
        $cits = $this->query('g.V().hasLabel("Class", "Interface", "Trait").values("fullnspath").unique()')->toArray();
        $namespaces = $this->query('g.V().hasLabel("Namespace").values("fullnspath").unique()')->toArray();
        
        $all = array_merge($cits, $namespaces);
        if (empty($all)) {
            return;
        }
        
        $this->atomIs('Usenamespace')
             ->outIs('USE')
             ->analyzerIsNot(array('Classes/IsExtClass',
                                   'Interfaces/IsExtInterface',
                                   'Traits/IsExtTrait',
                                   'Composer/IsComposerNsname'))
             ->fullnspathIsNot($all);
        $this->prepareQuery();
    }
}

?>
