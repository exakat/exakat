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

class Status implements Tasks {
    public function run(\Config $config) {
        $project = $config->project;

        $client = new Client();
        
        $path = $config->projects_root.'/projects/'.$project;
        
        if (!file_exists($config->projects_root.'/projects/'.$project.'/')) {
            die("Project '$project' does not exists. Aborting\n");
        }

        if (filesize($config->projects_root.'/projects/'.$project.'/log/tokenizer.final.log') == 0) {
            print "tokenizer.final.log is OK\n";
        } else {
            print "tokenizer.final.log is KO : \n";
            print file_get_contents($config->projects_root.'/projects/'.$project.'/log/tokenizer.final.log');
        }
        $tokenizerLogTime = filemtime($config->projects_root.'/projects/'.$project.'/log/tokenizer.log');

        $res = shell_exec('tail '.$config->projects_root.'/projects/'.$project.'/log/tokenizer.log | grep "Remaining token to process :"');
        if (preg_match('/Remaining token to process : 1/s', $res)) {
            print "Tokenizing was OK\n";
        } else {
            print "Tokenizing failed : \n";
            print $res;
        }

        if (filesize($config->projects_root.'/projects/'.$project.'/log/errors.log') == 191) {
            print "Error.log is OK\n";
        } else {
            print "Error.log signal some problems : \n";
            print file_get_contents($config->projects_root.'/projects/'.$project.'/log/errors.log');

        }

        $res = shell_exec('tail -n 1 '.$config->projects_root.'/projects/'.$project.'/log/analyze.*.final.log| grep Done');
        $logs = array('analyze', 'appinfo', 'Coding_Conventions', 'Dead_code', 'Custom');
        if ($res == "Done\nDone\nDone\nDone\nDone\nDone\n") {
            foreach($logs as $log) {
                if (filemtime($config->projects_root.'/projects/'.$project.'/log/analyze.'.$log.'.final.log') < $tokenizerLogTime) {
                    print "analyze.$log.final.log is too old\n";
                }
            }
            print "All analyzes were OK\n";
        } else {
            foreach($logs as $log) {
                if (!file_exists($config->projects_root.'/projects/'.$project.'/log/analyze.'.$log.'.final.log')) {
                    print 'analyze.'.$log.".final.log not yet here\n";
                    continue 1;
                }
                $log_content = file_get_contents($config->projects_root.'/projects/'.$project.'/log/analyze.'.$log.'.final.log');
                if (trim(substr($log_content, -5)) != "Done") {
                    print $config->projects_root.'/projects/'.$project.'/log/analyze.'.$log.'.final.log is wrong'."\n";
                    if (preg_match('#\[\[\'analyzer\':\'Analyzer\\\\\\\\(.+?)\\\\\\\\(.+?)\'\]\]#s', $log_content, $r)) {
                        print "   php bin/analyze -P $r[1]/$r[2] \n";
                    }
                } else {
                    print $config->projects_root.'/projects/'.$project.'/log/analyze.'.$log.'.final.log is OK'."\n";
                }
            }
            print "Some analyzes are KO\n";
        }

        if (file_exists($config->projects_root.'/projects/'.$project.'/report')) {
            if (filemtime($config->projects_root.'/projects/'.$project.'/report') < $tokenizerLogTime) {
                print " Report is too old\n";
            } else {       
               print " Report OK\n";
            }
        } else {
           print " Report KO\n";
        }
        
    }
}

?>