<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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


namespace Tasks;

class Clean implements Tasks {
    public function run(\Config $config) {
        $project = $config->project;

        $dirsToErase = array('log',
                             'report',
                             'Premier-ace',
                             );
        foreach($dirsToErase as $dir) {
            shell_exec('rm -rf '.$config->projects_root.'/projects/'.$config->project.'/'.$dir);
        }

        // rebuild log
        mkdir($config->projects_root.'/projects/'.$config->project.'/log', 0755);

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
                              'counts.sqlite',
                              'report.html',
                              'report.md',
                              'report.odt',
                              'report.pdf',
                              'report.sqlite',
                              'report.txt',
                              'report.zip',
                              'EchoWithConcat.json',
                              'PhpFunctions.json',
                              'bigArrays.txt',
                              'counts.sqlite',
                              'datastore.sqlite',
                              'stats.txt'
                             );
        $total = 0;
        foreach($filesToErase as $file) {
            $path = $config->projects_root.'/projects/'.$config->project.'/'.$file;
            if (file_exists($path)) {
                display('removing '.$file);
                unlink($path);
                ++$total;
            }
        }
        display("Removed $total files\n");
    }
}

?>
