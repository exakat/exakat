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

namespace Exakat\Analyzer\Cakephp;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\ClassUsage;
use Exakat\Analyzer\Common\InterfaceUsage;
use Exakat\Analyzer\Common\TraitUsage;
use Exakat\Data\CakePHP;

class CakePHPMissing extends Analyzer {
    protected $component  = 'cakephp';
    protected $version    = null;
    
    public function dependsOn() {
        return array('Cakephp/CakePHPUsed',
                    );
    }
    
    public function analyze() {
        $data = new CakePHP("{$this->config->dir_root}/data", $this->config->is_phar);

        $classes    = $data->getClasses($this->component, $this->version);
        if (empty($classes)) {
            $classes = array();
        } else {
            $classes    = call_user_func_array('array_merge', array_values($classes));
            $classes    = array_keys(array_count_values($classes));
            $classes    = makeFullNsPath($classes);
        }

        $interfaces =  $data->getInterfaces($this->component, $this->version);
        if (empty($interfaces)) {
            $interfaces = array();
        } else {
            $interfaces = call_user_func_array('array_merge', array_values($interfaces));
            $interfaces = array_keys(array_count_values($interfaces));
            $interfaces = makeFullNsPath($interfaces);
        }

        $traits     =  $data->getTraits($this->component, $this->version);
        if (empty($traits)) {
            $traits = array();
        } else {
            $traits     = call_user_func_array('array_merge', array_values($traits));
            $traits     = array_keys(array_count_values($traits));
            $traits     = makeFullNsPath($traits);
        }

        $this->analyzerIs('Cakephp/CakePHPUsed')
             ->fullnspathIsNot(array_merge($classes, $interfaces, $traits));
        $this->prepareQuery();
    }
}

?>
