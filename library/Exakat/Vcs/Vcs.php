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

namespace Exakat\Vcs;

abstract class Vcs {
    protected $destination = '';
    protected $destinationFull = '';
    
    public function __construct($destination, $project_root){
        $this->destination = $destination;
        $this->destinationFull = $project_root.'/projects/'.$destination;
    }

    abstract public function clone($source);

    abstract public function update();
    
    static public function getVcs($config) {
        if ($config->git === true) {
            return 'Git';
        } elseif ($config->svn === true) {
            return 'Svn';
        } elseif ($config->hg === true) {
            return 'Mercurial';
        } elseif ($config->bzr === true) {
            return 'Bazaar';
        } elseif ($config->composer === true) {
            return 'Composer';
        } elseif ($config->symlink === true) {
            return 'Symlink';
        } elseif ($config->tbz === true) {
            return 'Tarbz';
        } elseif ($config->tgz === true) {
            return 'Targz';
        } elseif ($config->zip === true) {
            return 'Zip';
        } elseif ($config->copy === true) {
            return 'Copy';
        } else {
            return 'EmptyCode';
        }
    }

    public function getStatus() {
        $status = array('updatable' => false,
                       );

        return $status;
    }
}

?>