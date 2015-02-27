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

class Report implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $reportClass = "\\Report\\Report\\".ucfirst(strtolower($config->report));

        if (!class_exists("\\Report\\Format\\".$config->format)) {
            die("Format '{$config->format}' doesn't exist. Choose among : ".join(", ", \Report\Report::$formats)."\nAborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$config->project)) {
            die("Project '{$config->project} doesn't exist yet. Run init to create it.\nAborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$config->project.'/datastore.sqlite')) {
            die("Project hasn't been analyzed. Run project first.\nAborting\n");
        }

        $datastore = new \Datastore($config);
        \Analyzer\Analyzer::$datastore = $datastore;

        $client = new Client();

        display( "Building report ".$config->report." for project ".$config->project." in file ".$config->file.", with format ".$config->format."\n");
        $begin = microtime(true);

        $report = new $reportClass($config->project, $client);
        $report->prepare();
        $size = $report->render($config->format, $config->filename);

        $end = microtime(true);
        display( "Processing time : ".number_format($end - $begin, 2)." s\n");
        display( "Done\n");
    }
}

?>