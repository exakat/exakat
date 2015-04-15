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

use Everyman\Neo4j\Client,
    Everyman\Neo4j\Gremlin\Query;

class Clean implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $project = $config->project;

        $dirsToErase = array('log',
                             'report',
                             'Premier-ace',
                             );
        foreach($dirsToErase as $dir) {
            shell_exec('rm -rf '.$config->projects_root.'/projects/'.$config->project.'/'.$dir);
        }

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
                              'report.sqlite',
                              'report.txt',
                              'report.zip',
                             );
        foreach($filesToErase as $file) {
            $path = $config->projects_root.'/projects/'.$config->project.'/'.$file;
            if (file_exists($path)) {
                unlink($path);
            }
        }

        // rebuild log
        mkdir($config->projects_root.'/projects/'.$config->project.'/log', 0755);

    }
}

?>
