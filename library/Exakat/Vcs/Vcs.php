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

namespace Exakat\Vcs;

use Exakat\Config;

abstract class Vcs {
    const SUPPORTED_VCS = array('git', 'svn', 'cvs', 'bzr', 'hg',
                                'composer',
                                'tgz', 'tbz', 'zip', 'rar', 'sevenz',
                                'none', 'symlink', 'copy');

    protected $destination     = '';
    protected $destinationFull = '';

    protected $branch = '';
    protected $tag    = '';

    protected $checked = false;

    const NO_UPDATE = 'No update';

    public function __construct($destination, $code_dir) {
        $this->destination     = $destination;
        $this->destinationFull = $code_dir;
    }

    abstract public function clone(string $source): void;

    public function getDiffLines($r1, $r2): array {
        return array();
    }

    public function getName() {
        $path = explode('\\', static::class);
        return strtolower(array_pop($path));
    }

    protected function check() {
        if ($this->checked === true) {
            return true;
        }

        $this->selfCheck();
        $this->checked = true;

        return true;
    }

    protected function selfCheck() {
    }

    public function getLineChanges() {
        return array();
    }

    public function update() {
        return self::NO_UPDATE;
    }

    public static function getVcs(Config $config) {
        if ($config->svn === true) {
            return Svn::class;
        } elseif ($config->hg === true) {
            return Mercurial::class;
        } elseif ($config->bzr === true) {
            return Bazaar::class;
        } elseif ($config->composer === true) {
            return Composer::class;
        } elseif ($config->symlink === true) {
            return Symlink::class;
        } elseif ($config->tbz === true) {
            return Tarbz::class;
        } elseif ($config->tgz === true) {
            return Targz::class;
        } elseif ($config->zip === true) {
            return Zip::class;
        } elseif ($config->copy === true) {
            return Copy::class;
        } elseif ($config->rar === true) {
            return Rar::class;
        } elseif ($config->sevenz === true) {
            return SevenZ::class;
        } elseif ($config->cvs === true) {
            return Cvs::class;
        } elseif ($config->none === true) {
            return None::class;
        } elseif ($config->git === true) {
            return Git::class;
        } else {
            return None::class;
        }
    }

    public function getStatus(): array {
        $status = array('updatable' => false,
                       );

        return $status;
    }

    public function setBranch(string $branch = ''): void {
        $this->branch = $branch;
    }

    public function setTag(string $tag = ''): void {
        $this->tag = $tag;
    }

    public function getFileModificationLoad(): array {
        return array();
    }

    public function getLastCommitDate(): int {
         return 0;
    }

}

?>