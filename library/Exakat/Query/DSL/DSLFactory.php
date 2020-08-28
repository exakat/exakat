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
declare(strict_types = 1);

namespace Exakat\Query\DSL;

use Exakat\Exceptions\UnknownDsl;
use Exakat\GraphElements;
use Exakat\Analyzer\Analyzer;

class DSLFactory {
    const VARIABLE_WRITE = true;
    const VARIABLE_READ  = false;

    public $availableAtoms         = array();
    public $availableLinks         = array();
    public $availableFunctioncalls = array();
    private $availableVariables     = array(); // This one is per query
    protected $availableLabels        = array(); // This one is per query
    protected $ignoredcit             = array();
    protected $ignoredfunctions       = array();
    protected $ignoredconstants       = array();
    protected $dictCode               = null;
    protected $datastore              = null;
    protected $linksDown              = '';
    protected $dependsOn              = array();
    protected $analyzerQuoted         = '';
    protected $MAX_LOOPING            = Analyzer::MAX_LOOPING;

    public function __construct(string $analyzer,
                                array $dependsOn = array()) {
        $this->dependsOn = $dependsOn;
        $this->analyzerQuoted = $analyzer;


        $this->dictCode  = exakat('dictionary');
        $this->datastore = exakat('datastore');

        $this->linksDown = GraphElements::linksAsList();

        if (empty($this->availableAtoms)) {
            $data = $this->datastore->getCol('TokenCounts', 'token');

            $this->availableAtoms = array('Project',
                                          'File',
                                          'Virtualproperty',
                                          'Analysis',
                                          'Noresult',
                                          'Void',
                                          );
            $this->availableLinks = array('DEFINITION',
                                          'ANALYZED',
                                          'PROJECT',
                                          'FILE',
                                          'OVERWRITE',
                                          'PPP',
                                          'DEFAULT',
                                          'RETURNED',
                                          );

            foreach($data as $token){
                if ($token === strtoupper($token)) {
                    $this->availableLinks[] = $token;
                } else {
                    $this->availableAtoms[] = $token;
                }
            }

            $this->availableFunctioncalls = $this->datastore->getCol('functioncalls', 'functioncall');

            $this->ignoredcit       = $this->datastore->getCol('ignoredcit',       'fullnspath');
            $this->ignoredfunctions = $this->datastore->getCol('ignoredfunctions', 'fullnspath');
            $this->ignoredconstants = $this->datastore->getCol('ignoredconstants', 'fullnspath');
        }
    }

    public function factory(string $name): Dsl {
        if (strtolower($name) === '_as') {
            $className = __NAMESPACE__ . '\\_As';
        } else {
            $className = __NAMESPACE__ . '\\' . ucfirst($name);
        }

        if (!class_exists($className)) {
            throw new UnknownDsl($name);
        }

        return new $className($this,
                              $this->availableAtoms,
                              $this->availableLinks,
                              $this->availableFunctioncalls,
                              $this->availableVariables,
                              $this->availableLabels,
                              $this->ignoredcit,
                              $this->ignoredfunctions,
                              $this->ignoredconstants,
                              $this->dependsOn,
                              $this->analyzerQuoted
                              );
    }
}

?>
