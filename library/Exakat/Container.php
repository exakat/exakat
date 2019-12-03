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

namespace Exakat;

use Exakat\Config;
use Exakat\Datastore;
use Exakat\Data\Dictionary;
use Exakat\Graph\Graph;
use Exakat\Reports\Helpers\Docs;

class Container {
    private $verbose   = 0;
    private $phar      = 0;
    private $config    = null;
    private $graphdb   = null;
    private $datastore = null;
    private $docs      = null;
    
    public function init() {
        $this->config = new Config($GLOBALS['argv']);

        $this->verbose = $this->config->verbose;
        $this->phar    = $this->config->isPhar;

        $this->graphdb    = Graph::getConnexion($this->config);
        $this->datastore  = Datastore::getDatastore($this->config);
        $this->dictionary = new Dictionary($this->datastore);

        $this->docs = new Docs($this->config->dir_root, 
                               $this->config->ext, 
                               $this->config->dev,
                               );

    }
    
    public function __get(string $what) {
        assert(property_exists($this, $what), "No such element in the container : '$what'\n");

        return $this->$what;
    }
}

?>
