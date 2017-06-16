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

namespace Exakat\Analyzer\Slim;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\ClassUsage;
use Exakat\Analyzer\Common\InterfaceUsage;
use Exakat\Analyzer\Common\TraitUsage;
use Exakat\Analyzer\Common\UsesFramework;
use Exakat\Data\Slim;

class UseSlim extends UsesFramework {
    public function analyze() {
        $data = new Slim($this->config->dir_root.'/data', $this->config);

        $this->classes    = call_user_func_array('array_merge', array_values($data->getClasses()));
        $this->classes    = array_keys(array_count_values($this->classes));

        $this->interfaces = call_user_func_array('array_merge', array_values($data->getInterfaces()));
        $this->interfaces = array_keys(array_count_values($this->interfaces));

        $this->traits = call_user_func_array('array_merge', array_values($data->getTraits()));
        $this->traits = array_keys(array_count_values($this->traits));
        
        parent::analyze();
    }
}

?>
