<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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
use Exakat\Data\ZendF2;

class UndefinedClasses extends Analyzer {
    protected $release = '1.12';
    
    public function dependsOn() {
        return array('ZendF/ZendClasses',
                     'ZendF/ZendTrait',
                     'ZendF/ZendInterfaces',
                     'ZendF/ZendTypehinting'
                     );
    }
        
    public function analyze() {
        if (in_array($this->release, array('1.5', '1.6', '1.7', '1.8', '1.9', '1.10', '1.11', '1.12'))) {
            $data = new ZendF($this->config->dir_root.'/data', $this->config);
        } elseif (in_array($this->release, array('2.0', '2.1', '2.2', '2.3', '2.4', '2.5', '3.0'))) {
            $data = new ZendF2($this->config->dir_root.'/data', $this->config);
        } else {
            $this->release = '2.5';
            $data = new ZendF2($this->config->dir_root.'/data', $this->config);
        }

        $classes = $data->getClassByRelease($this->release);
        $classes = makeFullNsPath(array_pop($classes));

        $interfaces = $data->getInterfaceByRelease($this->release);
        $interfaces = makeFullNsPath(array_pop($interfaces));

        $traits = $data->getTraitByRelease($this->release);
        $traits = makeFullNsPath(array_pop($traits));
        
        if (!empty($classes)) {
            $this->analyzerIs('ZendF/ZendClasses')
                 ->fullnspathIsNot($classes);
            $this->prepareQuery();
        }

        if (!empty($traits)) {
            $this->analyzerIs('ZendF/ZendTrait')
                 ->fullnspathIsNot($traits);
            $this->prepareQuery();
        }

        if (!empty($interfaces)) {
            $this->analyzerIs('ZendF/ZendInterfaces')
                 ->fullnspathIsNot($interfaces);
            $this->prepareQuery();
        }

        $classesInterfaces = array_merge($classes, $interfaces);
        if (!empty($classesInterfaces)) {
            $this->analyzerIs('ZendF/ZendTypehinting')
                 ->fullnspathIsNot($classesInterfaces);
            $this->prepareQuery();
        }
        // Add support for instanceof ?
    }
}

?>
