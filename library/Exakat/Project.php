<?php
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


namespace Exakat;

class Project {
    private $project  = 'unnamed';
    private $error    = '';

    public function __construct($project) {
        $this->project = $project;
    }

    public function validate() {
        if (strpos($this->project, DIRECTORY_SEPARATOR) !== false) {
            $this->error = 'Project name can\'t use ' . DIRECTORY_SEPARATOR;
            return false;
        }

        if (in_array(mb_strtolower($this->project), array('onepage', '.', '..', '...'))) {
            $this->error = 'Project name can\'t use reserved keyword ' . $this->project;
            return false;
        }

        if (preg_match_all('/[^\w_\.\-]/u', $this->project, $r)) {
            $this->error = 'Project name can\'t use those chars : "' . implode('", "', $r[0]) . '"';
            return false;
        }

        return true;
    }

    public function __toString() {
        return $this->project;
    }
    
    public function getError() {
        return $this->error;
    }
}

?>
