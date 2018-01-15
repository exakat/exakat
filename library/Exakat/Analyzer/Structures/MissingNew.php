<?php
/*
 * Copyright 2012-2017 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

namespace Exakat\Analyzer\Structures;

use Exakat\Analyzer\Analyzer;

class MissingNew extends Analyzer {
    public function analyze() {
        $customClasses = $this->query(<<<GREMLIN
g.V().hasLabel('Class').values('fullnspath').unique();
GREMLIN
);

        $phpClasses = $this->loadIni('php_classes.ini', 'classes');
        
        $classes = array_unique(array_merge($phpClasses, $customClasses->toArray()));
        $classes = $this->makeFullnspath($classes);
        
        $equal = $this->dictCode->translate('=');
        
        if (empty($equal)) {
            return ;
        }

        $this->atomIs('Functioncall')
             ->raw('or( where( __.in("ARGUMENT")), 
                        where( __.in("RIGHT").hasLabel("Assignation").has("code", '.$equal[0].') ) )')
             ->tokenIs(array('T_STRING', 'T_NS_SEPARATOR'))
             ->hasNoFunctionDefinition()
             ->fullnspathIs($classes);
        $this->prepareQuery();

        $this->atomIs(array('Identifier', 'Nsname'))
             ->raw('or( where( __.in("ARGUMENT")), 
                        where( __.in("RIGHT").hasLabel("Assignation").has("code", '.$equal[0].') ) )')
             ->hasNoConstantDefinition()
             ->fullnspathIs($classes, self::CASE_INSENSITIVE);
        $this->prepareQuery();
    }
}

?>
