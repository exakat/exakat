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


namespace Analyzer\Classes;

use Analyzer;

class UnresolvedClasses extends Analyzer\Analyzer {
    public function dependsOn() {
        return array('Analyzer\\Classes\\IsExtClass',
                     'Analyzer\\Classes\\IsVendor',);
    }

    public function analyze() {
        $classes = $this->loadIni('php_classes.ini');
        $classes = $classes['classes'];
        $classes = $this->makeFullNsPath($classes);
        
        $this->atomIs("New")
             ->outIs('NEW')
             ->noClassDefinition()
             ->analyzerIsNot('Analyzer\\Classes\\IsExtClass')
             ->analyzerIsNot('Analyzer\\Classes\\IsVendor')
             ->fullnspathIsNot($classes);
        $this->prepareQuery();
        
        // also add property/constant/methods/catch/try/typehint
    }
}

?>
