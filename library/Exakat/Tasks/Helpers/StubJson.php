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

class StubJson {
    private $json = null;
    private $file = null;

    public function __construct(string $path) {
        $this->json = json_decode(file_get_contents($path) ?? '');
        $this->file = basename($path);
    }

    public function getFile(): string {
        return $this->file;
    }

    public function getFunctions(): array {
        $return = array();

        foreach((array) $this->json->versions as $namespace => $space) {
            foreach($space->functions ?? array() as $name => $function) {
                $return[] = mb_strtolower($namespace . $name);
            }
        }

        return $return;
    }

    public function getClasses(): array {
        $return = array();

        foreach((array) $this->json->versions as $namespace => $space) {
            foreach($space->classes ?? array() as $name => $classe) {
                $return[] = mb_strtolower($namespace . $name);
            }
        }

        return $return;
    }

    public function getConstants(): array {
        $return = array();

        foreach((array) $this->json->versions as $namespace => $space) {
            foreach($space->constants ?? array() as $name => $constant) {
                // constant name is untouched (case insensitive)
                $return[] = mb_strtolower($namespace) . $name;
            }
        }

        return $return;
    }

    public function getInterfaces(): array {
        $return = array();

        foreach((array) $this->json->versions as $namespace => $space) {
            foreach($space->interfaces ?? array() as $name => $interface) {
                $return[] = mb_strtolower($namespace . $name);
            }
        }

        return $return;
    }

    public function getTraits(): array {
        $return = array();

        foreach((array) $this->json->versions as $namespace => $space) {
            foreach($space->traits ?? array() as $name => $trait) {
                $return[] = mb_strtolower($namespace . $name);
            }
        }

        return $return;
    }

    public function getClassMethods(): array {
        $return = array(array());

        foreach((array) $this->json->versions as $namespace => $space) {
            foreach($space->classes ?? array() as $name => $classe) {
                $return[mb_strtolower($namespace . $name)] = array_map('mb_strtolower', array_keys( (array) ($classe->methods ?? array()) ));
            }
        }

        return $return;
    }


    public function getClassProperties(): array {
        $return = array(array());

        foreach((array) $this->json->versions as $namespace => $space) {
            foreach($space->classes ?? array() as $name => $classe) {
                $return[mb_strtolower($namespace . $name)] = array_keys((array) $classe->properties ?? array());
            }
        }

        return $return;
    }

    public function getClassConstants(): array {
        $return = array(array());

        foreach((array) $this->json->versions as $namespace => $space) {
            foreach($space->classes ?? array() as $name => $classe) {
                $return[mb_strtolower($namespace . $name)] = array_keys((array) ($classe->constants ?? array()));
            }
        }

        return $return;
    }
}

?>
