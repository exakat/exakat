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


namespace Exakat\Tasks;

use Exakat\Config;
use Exakat\Datastore;
use Exakat\Exceptions\ProjectNeeded;
use Exakat\Exceptions\NoSuchProject;

class Clean extends Tasks {
    const CONCURENCE = self::ANYTIME;

    protected $logname = self::LOG_NONE;

    public function run() {
        if ($this->config->project === 'default') {
            throw new ProjectNeeded();
        }

        $path = $this->config->projects_root.'/projects/'.$this->config->project;
        if (!file_exists($path)) {
            throw new NoSuchProject($this->config->project);
        }

        display( "Cleaning project {$this->config->project}\n" );

        $dirsToErase = array('log',
                             'report',
                             'Premier-ace',
                             'faceted',
                             'faceted2',
                             'ambassador',
                             'oldreport',
                             );
        foreach($dirsToErase as $dir) {
            $dirPath = $path.'/'.$dir;
            if (file_exists($dirPath)) {
                display('removing '.$dir);
                rmdirRecursive($dirPath);
            }
        }

        // rebuild log
        mkdir($path.'/log', 0755);

        $filesToErase = array('Flat-html.html',
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
        $total = 0;
        foreach($filesToErase as $file) {
            $filePath = $path.'/'.$file;
            if (file_exists($filePath)) {
                display('removing '.$file);
                unlink($filePath);
                ++$total;
            }
        }
        display("Removed $total files\n");

        unset($this->datastore);
        $this->datastore = new Datastore($this->config, Datastore::CREATE);
        display("Recreating database\n");
    }
}

?>
