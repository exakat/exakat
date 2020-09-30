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

namespace Exakat\Reports;


class StubsIni extends Reports {
    const FILE_EXTENSION = 'ini';
    const FILE_FILENAME  = 'stubs';

    const INDENTATION = '    ';

    private $phpFunctions = array();
    private $phpCIT       = array();

    public function _generate(array $analyzerList): string {
        $ini = array();
        
        // constants
        $res = $this->dump->fetchTable('constants');
        $ini[] = "# Constants";
        if (empty($res->toArray())) {
            $ini[] = "constants[] = ;";
        } else {
            foreach($res->toArray() as $constant) {
                $ini[] = "constants[] = '{$constant['constant']}';";
            }
        }
        $ini[] = '';

        // functions
        $res = $this->dump->fetchTable('functions');
        $ini[] = "# Functions";
        foreach($res->toArray() as $function) {
            $ini[] = "functions[] = '{$function['function']}';";
        }
        $ini[] = '';

        // classes
        $res = $this->dump->fetchTable('cit');
        $classes = array();
        $cits = array('classes'    => array(),
                      'interfaces' => array(),
                      'traits'     => array(),
                     );
        foreach($res->toArray() as $cit) {
            $type = $cit['type'] === 'class' ? 'classes' : $cit['type'].'s';
            $cits[$type][] = $cit['name'];
            
            $classes[$cit['id']] = '\\'.$cit['name'];
        }
        foreach($cits as $type => $names) {
            $ini[] = "# ".ucfirst($type);
            if (empty($names)) {
                $ini[] = "{$type}[] = ;";
            } else {
                foreach($names as $name) {
                    $ini[] = "{$type}[] = '$name';";
                }
            }
            $ini[] = '';
        }
        
        // static methods
        $res = $this->dump->fetchTable('methods');
        $sm[] = "# Static Methods";
        $m[] = "# Methods";
        foreach($res->toArray() as $method) {
            if ($method['static'] == 1) {
                $sm[] = "staticMethods[] = '{$classes[$method['citId']]}::{$method['method']}';";
            } else {
                $m[] = "methods[] = '{$classes[$method['citId']]}::{$method['method']}';";
            }
        }
        if (count($sm) === 1) {
            $sp[] = "staticMethods[] = ;";
        }
        if (count($m) === 1) {
            $p[] = "methods[] = ;";
        }
        $sm[] = "";
        $m[] = "";
        $ini = array_merge($ini, $m, $sm);
        
        // static properties
        $res = $this->dump->fetchTable('properties');
        $sp[] = "# Static Properties";
        $p[] = "# Properties";
        foreach($res->toArray() as $property) {
            if ($property['static'] == 1) {
                $sp[] = "staticProperties[] = '{$classes[$property['citId']]}::{$property['property']}';";
            } else {
                $p[] = "properties[] = '{$classes[$property['citId']]}::{$property['property']}';";
            }
        }
        if (count($sp) === 1) {
            $sp[] = "staticProperties[] = ;";
        }
        if (count($p) === 1) {
            $p[] = "properties[] = ;";
        }
        $sp[] = "";
        $p[] = "";
        $ini = array_merge($ini, $p, $sp);

        // static properties
        $res = $this->dump->fetchTable('classconstants');
        $ini[] = "# Static Constants";
        if (empty($res->toArray())) {
            $ini[] = "staticConstants[] = ;";
        } else {
            foreach($res->toArray() as $constant) {
                $ini[] = "staticConstants[] = '{$classes[$constant['citId']]}::{$constant['constant']}';";
            }
        }
        $ini[] = '';

        // Ini
        return implode(PHP_EOL, $ini);
    }
}

?>