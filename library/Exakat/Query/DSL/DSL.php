<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

abstract class DSL {
    public static $availableAtoms         = array();
    public static $availableLinks         = array();
    public static $availableFunctioncalls = array();
    protected static $dictCode            = null;
    protected static $linksDown           = '';
    protected static $MAX_LOOPING         = Analyzer::MAX_LOOPING;

    public static function init($datastore) {
        self::$dictCode = Dictionary::factory($datastore);

        self::$linksDown = GraphElements::linksAsList();

        if (empty(self::$availableAtoms)) {
            $data = $datastore->getCol('TokenCounts', 'token');
            
            self::$availableAtoms = array('Project', 'File');
            self::$availableLinks = array('DEFINITION', 'ANALYZED', 'PROJECT', 'FILE');

            foreach($data as $token){
                if ($token === strtoupper($token)) {
                    self::$availableLinks[] = $token;
                } else {
                    self::$availableAtoms[] = $token;
                }
            }

            self::$availableFunctioncalls = $datastore->getCol('functioncalls', 'functioncall');
        }
    }

    public static function factory($name) {
        $className = __NAMESPACE__.'\\'.$name;
        
        if (!class_exists($className)) {
            throw new UnknownDsl($name);
        }
        return new $className();
    }
    
    abstract public function run();

    protected function assertAtom($atom) {
        if (is_string($atom)) {
            assert($atom !== 'Property', 'Property is no more');
            assert($atom === ucfirst(mb_strtolower($atom)), "Wrong format for atom name : '$atom");
        } else {
            foreach($atom as $a) {
                assert($a !== 'Property', 'Property is no more');
                assert($a === ucfirst(mb_strtolower($a)), "Wrong format for atom name : '$a'");
            }
        }
        return true;
    }

    protected function checkAtoms($atoms) {
        $atoms = makeArray($atoms);
        return array_values(array_intersect($atoms, self::$availableAtoms));
    }

    protected function SorA($value) {
        if (is_array($value)) {
            return makeList($value);
        } elseif (is_string($value)) {
            return '"'.$value.'"';
        } else {
            assert(false, '$v is not a string or an array');
        }
    }

    protected function assertLink($link) {
        if (is_string($link)) {
            assert(!in_array($link, array('KEY', 'ELEMENT', 'PROPERTY')), $link.' is no more');
            assert($link === strtoupper($link), 'Wrong format for LINK name : '.$link);
        } else {
            foreach($link as $l) {
                assert(!in_array($l, array('KEY', 'ELEMENT', 'PROPERTY')), $l.' is no more');
                assert($l === strtoupper($l), 'Wrong format for LINK name : '.$l);
            }
        }
        return true;
    }

    protected function assertProperty($property) {
        if (is_string($property)) {
            assert( ($property === mb_strtolower($property)) || ($property === 'noDelimiter') , 'Wrong format for property name : "'.$property.'"');
            assert(property_exists(Atom::class, $property) || ($property === 'label'), 'No such property in Atom : "'.$property.'"');
        } else {
            $properties = $property;
            foreach($properties as $property) {
                assert( ($property === mb_strtolower($property)) || ($property === 'noDelimiter'), "Wrong format for property name : '$property'");
                assert(property_exists(Atom::class, $property) || ($property === 'label'), "No such property in Atom : '$property'");
            }
        }
        return true;
    }
}

?>
