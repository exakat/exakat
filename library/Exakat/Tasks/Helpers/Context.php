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

class Context {
    const CONTEXT_CLASS        = 'Class';
    const CONTEXT_INTERFACE    = 'Interface';
    const CONTEXT_TRAIT        = 'Trait';
    const CONTEXT_FUNCTION     = 'Function';
    const CONTEXT_NEW          = 'New';
    const CONTEXT_NOSEQUENCE   = 'NoSequence';
    private $contexts = array(self::CONTEXT_CLASS        => array(0),
                              self::CONTEXT_INTERFACE    => array(0),
                              self::CONTEXT_TRAIT        => array(0),
                              self::CONTEXT_FUNCTION     => array(0),
                              self::CONTEXT_NEW          => array(0),
                              self::CONTEXT_NOSEQUENCE   => array(0),
                         );

    public function getCount(string $context = self::CONTEXT_NOSEQUENCE): bool {
        return $this->contexts[$context] !== array(0 => 0);
    }

    public function nestContext(string $context = self::CONTEXT_NOSEQUENCE): void {
        $this->contexts[$context][] = 0;
    }

    public function exitContext(string $context = self::CONTEXT_NOSEQUENCE): void {
        array_pop($this->contexts[$context]);
    }

    public function toggleContext(string $context): int {
        $toggled = 1 - $this->contexts[$context][count($this->contexts[$context]) - 1];
        $this->contexts[$context][count($this->contexts[$context]) - 1] = $toggled;
        return $toggled;
    }

    public function isContext(string $context): bool {
        return (bool) $this->contexts[$context][count($this->contexts[$context]) - 1];
    }
}

?>
