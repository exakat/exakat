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
    private $extFunctions = array();
    private $extConstants = array();
    private $extClasses   = array();

    public function __construct() {
        parent::__construct();

        $config = exakat('config');
        $rulesets = exakat('rulesets');

        $exts = $rulesets->listAllAnalyzer('Extensions');

        $constants = array(array());
        $classes   = array(array());
        $functions = array(array());
        foreach($exts as $ext) {
            $inifile = str_replace('Extensions\Ext', '', $ext);
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
        }

        $this->extConstants = array_merge(...$constants);
        $this->extFunctions = array_merge(...$functions);
        $this->extClasses   = array_merge(...$classes);
    }

    public function run(Atom $atom, array $extras): void {
        $id   = strrpos($atom->fullnspath ?? self::NOT_PROVIDED, '\\') ?: 0;
        $path = substr($atom->fullnspath ?? self::NOT_PROVIDED, $id);

        switch ($atom->atom) {
            case 'Functioncall' :
                if (in_array(makeFullnspath($path), $this->extFunctions, \STRICT_COMPARISON)) {
                    $atom->isExt = true;
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
                if (in_array($path, $this->extConstants, \STRICT_COMPARISON)) {
                    $atom->isExt = true;
                    break;
                }

                if (in_array(makeFullnspath($path) , $this->extClasses, \STRICT_COMPARISON)) {
                    $atom->isExt = true;
                    break;
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

            default :
                // Nothing
        }
    }
}

?>
