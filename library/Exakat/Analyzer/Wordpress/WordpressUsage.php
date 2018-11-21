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

namespace Exakat\Analyzer\Wordpress;

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\UsesFramework;
use Exakat\Data\Wordpress;

class WordpressUsage extends UsesFramework {
    public function analyze() {
        $data = new Wordpress($this->config->dir_root.'/data', $this->config->is_phar);
        
        $x = $data->getClasses('wordpress');
        if (empty($x)) {
            $this->classes    = array();
        } else {
            $this->classes    = array_values(array_unique(array_merge(...array_values($x))));
        }

        $x = $data->getInterfaces('wordpress');
        if (empty($x)) {
            $this->interfaces    = array();
        } else {
            $this->interfaces    = array_values(array_unique(array_merge(...array_values($x))));
        }

        $x = $data->getTraits('wordpress');
        if (empty($x)) {
            $this->traits    = array();
        } else {
            $this->traits    = array_values(array_unique(array_merge(...array_values($x))));
        }

        $x = $data->getNamespaces('wordpress');
        if (empty($x)) {
            $this->namespaces    = array();
        } else {
            $this->namespaces    = array_values(array_unique(array_merge(...array_values($x))));
        }

        parent::analyze();
    }
}

?>
