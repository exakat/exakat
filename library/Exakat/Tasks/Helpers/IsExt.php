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

class IsExt extends Plugin {
    public $name = 'isExt';
    public $type = 'boolean';
    private $extFunctions        = array();
    private $extConstants        = array();
    private $extClasses          = array();
    private $extInterfaces       = array();
    private $extClassConstants   = array();
    private $extClassMethods     = array();
    private $extClassProperties  = array();

    public function __construct() {
        parent::__construct();

        $config = exakat('config');
        $rulesets = exakat('rulesets');

        $exts = $rulesets->listAllAnalyzer('Extensions');

        $constants = array(array());
        $classes   = array(array());
        $functions = array(array());

        foreach($config->php_extensions ?? array() as $inifile) {
            if (!file_exists($config->dir_root . '/data/' . $inifile . '.ini')) {
                continue;
            }
            $ini = parse_ini_file($config->dir_root . '/data/' . $inifile . '.ini');

            if (!empty($ini['constants'][0])) {
                $constants[] = makeFullnspath($ini['constants'], \FNP_CONSTANT);
            }

            // Called class, handling CIT
            if (!empty($ini['classes'][0])) {
                $classes[] = makeFullnspath($ini['classes'], \FNP_NOT_CONSTANT);
            }
            if (!empty($ini['traits'][0])) {
                $classes[] = makeFullnspath($ini['traits'], \FNP_NOT_CONSTANT);
            }
            if (!empty($ini['interfaces'][0])) {
                $classes[] = makeFullnspath($ini['interfaces'], \FNP_NOT_CONSTANT);
            }

            if (!empty($ini['functions'][0])) {
                $functions[] = makeFullnspath($ini['functions'], \FNP_NOT_CONSTANT);
            }

            if (!empty($ini['staticMethods'][0])) {
                foreach($ini['staticMethods'] as $fullMethod) {
                    list($class, $method) = explode('::', $fullMethod, 2);
                    array_collect_by($this->extClassMethods, makeFullnspath($class),  mb_strtolower($method));
                }
            }

            if (!empty($ini['staticProperties'][0])) {
                foreach($ini['staticProperties'] as $fullProperty) {
                    list($class, $property) = explode('::', $fullProperty, 2);
                    array_collect_by($this->extClassProperties, makeFullnspath($class), $property);
                }
            }

            if (!empty($ini['staticConstants'][0])) {
                foreach($ini['staticConstants'] as $fullConstant) {
                    list($class, $constant) = explode('::', $fullConstant, 2);
                    array_collect_by($this->extClassConstants, makeFullnspath($class), $constant);
                }
            }
        }

        // Not doint $o->p and $o->m() ATM : needs $o's type.

        $this->extConstants = array_merge(...$constants);
        $this->extFunctions = array_merge(...$functions);
        $this->extClasses   = array_merge(...$classes);
    }

    public function run(Atom $atom, array $extras): void {
        $id   = strrpos($atom->fullnspath ?? self::NOT_PROVIDED, '\\') ?: 0;
        $path = substr($atom->fullnspath ?? self::NOT_PROVIDED, $id);

        switch ($atom->atom) {
            case 'Staticmethodcall' :
                $path = makeFullnspath($extras['CLASS']->fullnspath ?? self::NOT_PROVIDED);
                $method = mb_strtolower(substr($extras['METHOD']->fullcode ?? self::NOT_PROVIDED, 0, strpos($extras['METHOD']->fullcode ?? self::NOT_PROVIDED, '(')));
                if (in_array($method, $this->extClassMethods[$path] ?? array(), \STRICT_COMPARISON)) {
                    $atom->isExt = true;
                }
                break;

            case 'Staticproperty' :
                $path = makeFullnspath($extras['CLASS']->fullnspath ?? self::NOT_PROVIDED);
                if (in_array($extras['MEMBER']->code ?? self::NOT_PROVIDED, $this->extClassProperties[$path] ?? array(), \STRICT_COMPARISON)) {
                    $atom->isExt = true;
                }
                break;

            case 'Staticconstant' :
                $path = makeFullnspath($extras['CLASS']->fullnspath ?? self::NOT_PROVIDED);
                if (in_array($extras['CONSTANT']->code ?? self::NOT_PROVIDED, $this->extClassConstants[$path] ?? array(), \STRICT_COMPARISON)) {
                    $atom->isExt = true;
                }
                break;

            case 'Functioncall' :
                if (in_array(makeFullnspath($path), $this->extFunctions, \STRICT_COMPARISON)) {
                    $atom->isExt = true;
                }
                break;

            case 'Class' :
            case 'Classanonymous' :
                if (in_array($extras['EXTENDS']->fullnspath ?? self::NOT_PROVIDED, $this->extClasses, \STRICT_COMPARISON)) {
                    $extras['EXTENDS']->isExt = true;
                }

                foreach($extras['IMPLEMENTS'] ?? array() as $implements) {
                    if (in_array($implements->fullnspath ?? self::NOT_PROVIDED, $this->extInterfaces, \STRICT_COMPARISON)) {
                        $implements->isExt = true;
                    }
                }
                break;

            case 'Interface' :
                if (in_array($extras['EXTENDS']->fullnspath ?? self::NOT_PROVIDED, $this->extInterfaces, \STRICT_COMPARISON)) {
                    $extras['EXTENDS']->isExt = true;
                }
                break;

            case 'Constant' :
                $atom->isExt = false;
                $extras['NAME']->isExt = false;
                break;

            case 'Instanceof' :
                // Warning : atom->fullnspath for classes (no fallback)
                if (in_array(makeFullnspath($extras['CLASS']->fullnspath ?? self::NOT_PROVIDED), $this->extClasses, \STRICT_COMPARISON)) {
                    $extras['CLASS']->isExt = true;
                }
                break;

            case 'Newcall' :
                // Warning : atom->fullnspath for classes (no fallback)
                if (in_array(makeFullnspath($atom->fullnspath), $this->extClasses, \STRICT_COMPARISON)) {
                    $atom->isExt = true;
                }
                break;

            case 'Identifier' :
            case 'Nsname' :
                if (in_array($path, $this->extConstants, \STRICT_COMPARISON) &&
                    strpos($atom->fullcode, '\\', 1) === false) {
                    $atom->isExt = true;
                }
                break;

            case 'Isset' :
            case 'Isset' :
            case 'Empty' :
            case 'Unset' :
            case 'Exit'  :
            case 'Empty' :
            case 'Echo'  :
            case 'Print' :
                $atom->isExt = false;
                break;

            case 'Trait':
            default :
                // Nothing
        }
    }
}

?>
