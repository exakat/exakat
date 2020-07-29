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

class Section {
    private const SAME_AS_FILE = true;

    public $method  = 'NoSuchMethod';
    public $title   = 'No title';
    public $menu    = 'No menu title';
    public $source  = self::SAME_AS_FILE;
    public $file    = 'empty';
    public $icon    = 'circle-o';
    public $ruleset = 'None';

    public function __construct(array $section) {
        assert(isset($section['title']),  "Missing 'title' for section");
        assert(isset($section['file']),   "Missing 'file' for section");
        assert(isset($section['method']), "Missing 'method' for section");

        $this->title   = $section['title']   ?? $this->title;
        $this->menu    = $section['menu']    ?? $this->title;  // Yes, menu === title if not specified
        $this->file    = $section['file']    ?? $this->file;
        $this->source  = $section['source']  ?? $this->file;  // Yes, source == file if not specified
        $this->icon    = $section['icon']    ?? $this->icon;
        $this->method  = $section['method']  ?? $this->method;

        if (!isset($section['ruleset'])) {
            $this->ruleset = 'None';
        } elseif (is_array($section['ruleset'])) {
            $this->ruleset = $section['ruleset'];
        } elseif (is_string($section['ruleset'])) {
            $this->ruleset = array($section['ruleset']);
        }
    }

    public function __get($name) {
        display("Access to undefined property $name\n");
    }

    public function __set($name, $value) {
        display("Write to undefined property $name\n");
    }
}

?>