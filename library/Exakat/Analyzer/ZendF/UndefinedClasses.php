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
namespace Exakat\Analyzer\ZendF;

use Exakat\Analyzer\Analyzer;
use Exakat\Data\ZendF;

class UndefinedClasses extends Analyzer {
    protected $release = '1.12';
    
    public function dependsOn() {
        return array('ZendF/ZendClasses');
    }
        
    public function analyze() {
        if (!in_array($this->release, array('1.5', '1.6', '1.7', '1.8', '1.9', '1.10', '1.11', '1.12'))) {
            $this->release = '1.12';
        }

        $data = new ZendF();

        $classes = $data->getClassByRelease($this->release);
        $classes = $this->makeFullNSpath(array_pop($classes));
        $interfaces = $data->getInterfaceByRelease($this->release);
        $interfaces = $this->makeFullNSpath(array_pop($interfaces));
        $all = array_merge($classes, $interfaces);
        
        $this->analyzerIs('ZendF/ZendClasses')
             ->fullnspathIsNot($all);
        $this->prepareQuery();
    }
}

?>
