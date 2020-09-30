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

class Sequences {
    private const START_RANK = -1;

    private $sequences           = array();
    private $rank                = self::START_RANK;
    private $elements            = array();

    private $level               = 0;

    private $ranksPile           = array();
    private $elementsPile        = array();

    public function start(Atom $sequence): void {
        ++$this->level;

        $this->sequences[$this->level]    = $sequence;
        $this->ranksPile[$this->level]    = $this->rank;
        $this->elementsPile[$this->level] = $this->elements;

        $this->rank                    = self::START_RANK;
        $this->elements                = array();
    }

    public function add(Atom $element): void {
        ++$this->rank;
        $element->rank                        = $this->rank;
        $this->elements[]                     = $element;
        $this->sequences[$this->level]->count = $element->rank + 1;
    }

    public function getElements(): array {
        return $this->elements;
    }

    public function end(): Atom {
        assert($this->level > 0, "Trying to pop a non-existing sequence ($this->level)\n");

        array_pop($this->sequences);
        $this->rank     = array_pop($this->ranksPile);
        $this->elements = array_pop($this->elementsPile);

        --$this->level;

        return $this->sequences[$this->level];
    }
}

?>
