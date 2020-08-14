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

namespace Exakat\Tasks;

use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\NoSuchProject;

class Clean extends Tasks {
    const CONCURENCE = self::ANYTIME;

    protected $logname = self::LOG_NONE;

    private $filesToErase = array('Flat-html.html',
                                  'Flat-markdown.md',
                                  'Flat-sqlite.sqlite',
                                  'Flat-text.txt',
                                  'Premier-ace.zip',
                                  'Premier-html.html',
                                  'Premier-markdown.md',
                                  'Premier-sqlite.sqlite',
                                  'Premier-text.txt',
                                  'datastore.sqlite',
                                  'magicnumber.sqlite',
                                  'report.html',
                                  'report.md',
                                  'report.odt',
                                  'report.pdf',
                                  'report.json',
                                  'report.xml',
                                  'report.sqlite',
                                  'report.txt',
                                  'report.zip',
                                  'EchoWithConcat.json',
                                  'PhpFunctions.json',
                                  'bigArrays.txt',
                                  'counts.sqlite',
                                  'stats.txt',
                                  'dump.sqlite',
                                  'faceted.zip',
                                  'faceted2.zip',
                                 );

    public function run(): void {
        if ($this->config->project === 'default') {
            throw new ProjectNeeded();
        }

        if (!file_exists(dirname($this->config->code_dir))) {
            throw new NoSuchProject($this->config->project);
        }

        display( "Cleaning project {$this->config->project}\n");

        $dirsToErase = array('report',
                             'diplomat',
                             );
        foreach($dirsToErase as $dir) {
            $dirPath = "{$this->config->project_dir}/$dir";
            if (file_exists($dirPath)) {
                display("removing $dir");
                rmdirRecursive($dirPath);
            }
        }

        // rebuild tmp
        rmdirRecursive($this->config->tmp_dir);
        mkdir($this->config->tmp_dir, 0755);

        // rebuild log
        rmdirRecursive($this->config->log_dir);
        if (!file_exists($this->config->log_dir)) {
            mkdir($this->config->log_dir, 0755);
        }

        $total = 0;
        foreach($this->filesToErase as $file) {
            $filePath = "{$this->config->project_dir}/$file";
            if (file_exists($filePath)) {
                display("removing $file");
                unlink($filePath);
                ++$total;
            }
        }
        display("Removed $total files\n");

        $this->datastore->create();
        display("Recreating database\n");
    }
}

?>
