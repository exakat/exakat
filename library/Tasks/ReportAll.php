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

class ReportAll implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $formats = array('Markdown', 'Sqlite', 'Ace', 'Html', 'Text', /* 'pdf', 'odt' */);
        $reportType = 'Premier';
        
        foreach($formats as $format) {
            print "Reporting $format\n";
            $args = array ( 1 => 'report',
                            2 => '-p',
                            3 => $config->project,
                            4 => '-f',
                            5 => 'report',
                            6 => '-format',
                            7 => $format,
                            8 => '-report',
                            9 => $reportType,
                            );
            $config = \Config::factorySingle($args);
            
            $report = new Report();
            $report->run($config);
            unset($report);
        }
    }
}

?>