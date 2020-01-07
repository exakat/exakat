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
use Exakat\Data\Methods;
use Exakat\Analyzer\Rulesets;
use Exakat\Phpexec;

class Container {
    private $verbose    = 0;
    private $phar       = 0;

    private $config     = null;
    private $graphdb    = null;
    private $datastore  = null;
    private $dictionary = null;
    private $docs       = null;
    private $methods    = null;
    private $rulesets   = null;
    private $php        = null;
    
    public function init() {
        $this->config = new Config($GLOBALS['argv']);

        $this->verbose = $this->config->verbose;
        $this->phar    = $this->config->isPhar;
    }
    
    public function __get(string $what) {
        assert(property_exists($this, $what), "No such element in the container : '$what'\n");
        
        if ($this->$what === null) {
            $this->$what();
        }

        return $this->$what;
    }
    
    private function graphdb() {
        $this->graphdb    = Graph::getConnexion();
    }

    private function datastore() {
        $this->datastore  = new Datastore();
    }

    private function dictionary() {
        $this->dictionary = new Dictionary($this->datastore);
    }

    private function methods() {
        $this->methods    = new Methods($this->config);
    }

    private function docs() {
        $this->docs = new Docs($this->config->dir_root, 
                               $this->config->ext, 
                               $this->config->dev,
                               );
    }

    private function rulesets() {
        $this->rulesets = new Rulesets("{$this->config->dir_root}/data/analyzers.sqlite",
                                       $this->config->ext,
                                       $this->config->dev,
                                       $this->config->rulesets);
    }

    private function php() {
        $phpVersion = 'php' . str_replace('.', '', $this->config->phpversion);
        $this->php = new Phpexec($this->config->phpversion, $this->config->{$phpVersion});
    }
}

?>
