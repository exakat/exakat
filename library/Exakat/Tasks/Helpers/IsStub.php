<?php declare(strict_types = 1);
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

namespace Exakat\Tasks\Helpers;

class IsStub extends Plugin {
    public $name = 'isStub';
    public $type = 'boolean';

    private $stubFunctions        = array();
    private $stubConstants        = array();
    private $stubClasses          = array();
    private $stubInterfaces       = array();
    private $stubTraits           = array();
    private $stubClassConstants   = array();
    private $stubClassMethods     = array();
    private $stubClassProperties  = array();

    public function __construct() {
        parent::__construct();

        // collecter les données depuis stubs/*.json
        // collecter les données via stubs/*.php ?

        foreach(exakat('stubs') as $stub) {
            $this->stubFunctions        = $stub->getFunctions();
            $this->stubConstants        = $stub->getConstants();
            $this->stubClasses          = $stub->getClasses();
            $this->stubInterfaces       = $stub->getInterfaces();
            $this->stubTraits           = $stub->getTraits();

            $this->stubClassMethods     = $stub->getClassMethods();
            $this->stubClassProperties  = $stub->getClassProperties();
            $this->stubClassConstants   = $stub->getClassConstants();
        }
    }

    public function run(Atom $atom, array $extras): void {
        switch ($atom->atom) {
            case 'Staticmethodcall' :
                $method = mb_strtolower($extras['METHOD']->code ?? self::NOT_PROVIDED);
                if (in_array($method, $this->stubClassMethods[$extras['CLASS']->fullnspath] ?? array(), \STRICT_COMPARISON)) {
                    $atom->isStub = true;
                }
                break;

            case 'Staticproperty' :
                if (in_array($extras['MEMBER']->code ?? self::NOT_PROVIDED, $this->stubClassProperties[$extras['CLASS']->fullnspath] ?? array(), \STRICT_COMPARISON)) {
                    $atom->isStub = true;
                }
                break;

            case 'Staticconstant' :
                if (in_array($extras['CONSTANT']->code ?? self::NOT_PROVIDED, $this->stubClassConstants[$extras['CLASS']->fullnspath] ?? array(), \STRICT_COMPARISON)) {
                    $atom->isStub = true;
                }
                break;

            case 'Functioncall' :
                if (in_array($atom->fullnspath, $this->stubFunctions, \STRICT_COMPARISON)) {
                    $atom->isStub = true;
                }
                break;

            case 'Class' :
            case 'Classanonymous' :
                if (in_array($extras['EXTENDS']->fullnspath ?? self::NOT_PROVIDED, $this->stubClasses, \STRICT_COMPARISON)) {
                    $extras['EXTENDS']->isStub = true;
                }

                foreach($extras['IMPLEMENTS'] ?? array() as $implements) {
                    if (in_array($implements->fullnspath ?? self::NOT_PROVIDED, $this->stubInterfaces, \STRICT_COMPARISON)) {
                        $implements->isStub = true;
                    }
                }
                break;

            case 'Interface' :
                if (in_array($extras['EXTENDS']->fullnspath ?? self::NOT_PROVIDED, $this->stubInterfaces, \STRICT_COMPARISON)) {
                    $extras['EXTENDS']->isStub = true;
                }
                break;

            case 'Constant' :
                $atom->isStub = false;
                $extras['NAME']->isStub = false;
                break;

            case 'Instanceof' :
                // Warning : atom->fullnspath for classes (no fallback)
                if (in_array($extras['CLASS']->fullnspath ?? self::NOT_PROVIDED, $this->stubClasses, \STRICT_COMPARISON)) {
                    $extras['CLASS']->isStub = true;
                }
                break;

            case 'Newcall' :
                // Warning : atom->fullnspath for classes (no fallback)
                if (in_array($atom->fullnspath, $this->stubClasses, \STRICT_COMPARISON)) {
                    $atom->isStub = true;
                }
                break;

            case 'Usetrait' :
                foreach($extras as $extra) {
                    if (in_array($extra->fullnspath, $this->stubTraits, \STRICT_COMPARISON)) {
                        $extra->isStub = true;
                    }
                }
                // Warning : atom->fullnspath for classes (no fallback)
                break;

            case 'Identifier' :
            case 'Nsname' :
                if (in_array($atom->fullnspath, $this->stubConstants, \STRICT_COMPARISON)) { // No extra \\, besides the first one
                    $atom->isStub = true;
                    break;
                }
                break;

            case 'Trait': // explicit
            default :
                // Nothing
        }

    }
}

?>
