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

declare( strict_types = 1);

namespace Exakat\Tasks\Helpers;

class IsPhp extends Plugin {
    public $name = 'isPhp';
    public $type = 'boolean';
    private $phpFunctions = array();
    private $phpConstants = array();
    private $phpClasses = array();

    public function __construct() {
        parent::__construct();

        $config = exakat('config');
        $this->phpFunctions = parse_ini_file($config->dir_root.'/data/php_functions.ini')['functions'] ?? array();
        $this->phpFunctions = makeFullnspath($this->phpFunctions);

        $this->phpConstants = parse_ini_file($config->dir_root.'/data/php_constants.ini')['constants'] ?? array();
        $this->phpConstants = makeFullnspath($this->phpConstants, \FNP_CONSTANT);

        $this->phpClasses = parse_ini_file($config->dir_root.'/data/php_classes.ini')['classes'] ?? array();
        $this->phpClasses = makeFullnspath($this->phpClasses);
    }

    public function run(Atom $atom, array $extras): void {
        switch ($atom->atom) {
            case 'Functioncall' :
                $path = substr($atom->fullnspath, strrpos($atom->fullnspath, '\\'));
                if (in_array($path, $this->phpFunctions, \STRICT_COMPARISON)) {
                    $atom->isPhp = true;
                    $atom->fullnspath = $path;
                }
                break;

            case 'Constant' :
                $atom->isPhp = false;
                $extras['NAME']->isPhp = false;
                break;

            case 'Newcall' :
                $path = substr($atom->fullnspath, strrpos($atom->fullnspath, '\\'));
                if (in_array($path, $this->phpClasses, \STRICT_COMPARISON)) {
                    $atom->isPhp = true;
                    $atom->fullnspath = $path;
                }
                break;

            case 'Identifier' :
            case 'Nsname' :
                $path = substr($atom->fullnspath, strrpos($atom->fullnspath, '\\'));
                if (in_array($path, $this->phpConstants, \STRICT_COMPARISON)) {
                    $atom->isPhp = true;
                    $atom->fullnspath = $path;
                }
                break;

            default :
                // Nothing
        }
    }
}

?>
