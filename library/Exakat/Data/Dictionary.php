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
declare(strict_types = 1);

namespace Exakat\Data;


class Dictionary {
    const CASE_SENSITIVE   = true;
    const CASE_INSENSITIVE = false;

    private $datastore  = null;
    private $dictionary = array();
    private $lcindex    = array();

    public function __construct() {
        $this->datastore = exakat('datastore');
    }

    private function init(): void {
        $this->dictionary = $this->datastore->getAllHash('dictionary');
        foreach(array_keys($this->dictionary) as $key) {
            $this->lcindex[mb_strtolower((string) $key)] = 1;
        }
    }

    public function translate(array $code, bool $case = self::CASE_SENSITIVE): array {
        if (empty($this->dictionary)) {
            $this->init();
        }
        $return = array();

        $code = makeArray($code);

        if ($case === self::CASE_SENSITIVE) {
            $caseClosure = function (string $x) { return $x; };
        } else {
            $caseClosure = function (string $x) { return mb_strtolower($x); };
        }

        foreach($code as $c) {
            $d = $caseClosure($c);
            if (isset($this->dictionary[$d])) {
                $return[] = $this->dictionary[$d];
            }
        }

        return $return;
    }

    public function grep(string $regex): array {
        $keys = preg_grep($regex, array_keys($this->dictionary));

        $return = array();
        foreach($keys as $k) {
            $return[] = $this->dictionary[$k];
        }

        return $return;
    }

    public function source(array $code): array {
        $return = array();

        $reverse = array_flip($this->dictionary);

        foreach($code as $c) {
            if (isset($reverse[$c])) {
                $return[] = $reverse[$c];
            }
        }

        return $return;
    }

    public function length(string $length): array {
        $return = array();

        if (preg_match('/ > (\d+)/', $length, $r)) {
            $closure = function (string $s) use ($r) { return strlen($s) > $r[1]; };
        } elseif (preg_match('/ == (\d+)/', $length, $r)) {
            $closure = function (string $s) use ($r) { return strlen($s) === (int) $r[1]; };
        } elseif (preg_match('/ < (\d+)/', $length, $r)) {
            $closure = function (string $s) use ($r) { return strlen($s) < $r[1]; };
        } else {
            assert(false, "codeLength didn't understand $length");
        }

        $return = array_filter($this->dictionary, $closure, ARRAY_FILTER_USE_KEY);

        return array_values($return);
    }

    public function staticMethodStrings(): array {
        $doublecolon = array_filter($this->dictionary, function ($x) { return strlen($x) > 6 &&
                                                                              strpos($x,' ') === false &&
                                                                              strpos($x,'::') !== false &&
                                                                              mb_strtolower($x) === $x;},
                                                                              ARRAY_FILTER_USE_KEY );

        $return = array();
        foreach($doublecolon as $key => $value) {
            // how can this regex fail ?
            if (preg_match('/^[\'"](.+?)::(.+?)/', $key, $r)) {
                $return['\\' . $r[1]] = $value;
            }
        }

        return $return;
    }
}
