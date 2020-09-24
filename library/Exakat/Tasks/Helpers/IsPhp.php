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

class IsPhp extends Plugin {
    public $name = 'isPhp';
    public $type = 'boolean';
    private $phpFunctions        = array();
    private $phpConstants        = array();
    private $phpClasses          = array();
    private $phpInterfaces       = array();
    private $phpClassConstants   = array();
    private $phpClassMethods     = array();
    private $phpClassProperties  = array();

    public function __construct() {
        parent::__construct();

        $config = exakat('config');
        $this->phpFunctions = parse_ini_file($config->dir_root . '/data/php_functions.ini')['functions'] ?? array();
        $this->phpFunctions = makeFullnspath($this->phpFunctions);

        $this->phpConstants = parse_ini_file($config->dir_root . '/data/php_constants.ini')['constants'] ?? array();
        $this->phpConstants = makeFullnspath($this->phpConstants, \FNP_CONSTANT);

        $this->phpClasses = parse_ini_file($config->dir_root . '/data/php_classes.ini')['classes'] ?? array();
        $this->phpClasses = makeFullnspath($this->phpClasses);

        $this->phpInterfaces      = array();
        $this->phpTraits          = array();
        $this->phpClassConstants  = array(); //array('\ziparchive' => array('CREATE'),);
        $this->phpClassMethods    = array(); //array('\test' => array('method'),);
        $this->phpClassProperties = array(); //array('\test' => array('$property'),);
    }

    public function run(Atom $atom, array $extras): void {
        $id = strrpos($atom->fullnspath ?? self::NOT_PROVIDED, '\\') ?: 0;
        $path = substr($atom->fullnspath ?? self::NOT_PROVIDED, $id);

        switch ($atom->atom) {
            case 'Staticmethodcall' :
                $path = makeFullnspath($extras['CLASS']->fullnspath ?? self::NOT_PROVIDED);
                $method = mb_strtolower(substr($extras['METHOD']->fullcode ?? self::NOT_PROVIDED, 0, strpos($extras['METHOD']->fullcode ?? self::NOT_PROVIDED, '(')));

                if (in_array($method, $this->phpClassMethods[$path] ?? array(), \STRICT_COMPARISON)) {
                    $atom->isPhp = true;
                }
                break;

            case 'Staticproperty' :
                $path = makeFullnspath($extras['CLASS']->fullnspath ?? self::NOT_PROVIDED);
                if (in_array($extras['MEMBER']->code ?? self::NOT_PROVIDED, $this->phpClassProperties[$path] ?? array(), \STRICT_COMPARISON)) {
                    $atom->isPhp = true;
                }
                break;

            case 'Staticconstant' :
                $path = makeFullnspath($extras['CLASS']->fullnspath ?? self::NOT_PROVIDED);
                if (in_array($extras['CONSTANT']->code ?? self::NOT_PROVIDED, $this->phpClassConstants[$path] ?? array(), \STRICT_COMPARISON)) {
                    $atom->isPhp = true;
                }
                break;

            case 'Functioncall' :
                if (in_array(makeFullnspath($path), $this->phpFunctions, \STRICT_COMPARISON)) {
                    $atom->isPhp = true;
                }
                break;

            case 'Class' :
            case 'Classanonymous' :
                if (in_array($extras['EXTENDS']->fullnspath ?? self::NOT_PROVIDED, $this->phpClasses, \STRICT_COMPARISON)) {
                    $extras['EXTENDS']->isPhp = true;
                }

                foreach($extras['IMPLEMENTS'] ?? array() as $implements) {
                    if (in_array($implements->fullnspath ?? self::NOT_PROVIDED, $this->phpInterfaces, \STRICT_COMPARISON)) {
                        $implements->isPhp = true;
                    }
                }
                break;

            case 'Interface' :
                if (in_array($extras['EXTENDS']->fullnspath ?? self::NOT_PROVIDED, $this->phpInterfaces, \STRICT_COMPARISON)) {
                    $extras['EXTENDS']->isPhp = true;
                }
                break;

            case 'Constant' :
                $atom->isPhp = false;
                $extras['NAME']->isPhp = false;
                break;

            case 'Instanceof' :
                // Warning : atom->fullnspath for classes (no fallback)
                if (in_array(makeFullnspath($extras['CLASS']->fullnspath ?? self::NOT_PROVIDED), $this->phpClasses, \STRICT_COMPARISON)) {
                    $extras['CLASS']->isPhp = true;
                }
                break;

            case 'Newcall' :
                // Warning : atom->fullnspath for classes (no fallback)
                if (in_array(makeFullnspath($atom->fullnspath), $this->phpClasses, \STRICT_COMPARISON)) {
                    $atom->isPhp = true;
                }
                break;

            case 'Identifier' :
            case 'Nsname' :
                if (in_array($path, $this->phpConstants, \STRICT_COMPARISON) &&
                    strpos($atom->fullcode, '\\', 1) === false) { // No extra \\, besides the first one
                    $atom->isPhp = true;
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
                $atom->isPhp = true;
                break;

            case 'Trait':
            default :
                // Nothing
        }

    }
}

?>
