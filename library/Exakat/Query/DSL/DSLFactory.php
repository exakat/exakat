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


namespace Exakat\Query\DSL;

use Exakat\Exceptions\UnknownDsl;
use Exakat\Data\Dictionary;
use Exakat\Tasks\Helpers\Atom;
use Exakat\GraphElements;
use Exakat\Analyzer\Analyzer;

class DSLFactory {
    const VARIABLE_WRITE = true;
    const VARIABLE_READ  = false;
    
    public     $availableAtoms         = array();
    public     $availableLinks         = array();
    public     $availableFunctioncalls = array();
    private    $availableVariables     = array(); // This one is per query
    protected  $availableLabels        = array('first'); // This one is per query
    protected  $dictCode             = null;
    protected  $linksDown            = '';
    protected  $MAX_LOOPING          = Analyzer::MAX_LOOPING;

    public function __construct($datastore) {
        $this->dictCode = Dictionary::factory($datastore);

        $this->linksDown = GraphElements::linksAsList();

        if (empty($this->availableAtoms)) {
            $data = $datastore->getCol('TokenCounts', 'token');
            
            $this->availableAtoms = array('Project', 'File');
            $this->availableLinks = array('DEFINITION', 'ANALYZED', 'PROJECT', 'FILE', 'OVERWRITE');

            foreach($data as $token){
                if ($token === strtoupper($token)) {
                    $this->availableLinks[] = $token;
                } else {
                    $this->availableAtoms[] = $token;
                }
            }

            $this->availableFunctioncalls = $datastore->getCol('functioncalls', 'functioncall');
        }
    }

    public function factory($name) {
        if (strtolower($name) === '_as') {
            $className = __NAMESPACE__.'\\_As';
        } else {
            $className = __NAMESPACE__.'\\'.ucfirst($name);
        }
        
        if (!class_exists($className)) {
            throw new UnknownDsl($name);
        }
        
        return new $className($this, $this->dictCode, $this->availableAtoms, $this->availableLinks, $this->availableFunctioncalls, $this->availableVariables, $this->availableLabels);
    }
}

?>
