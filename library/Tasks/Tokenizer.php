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

class Tokenizer implements Tasks {
    public function run(\Config $config) {
        $begin = microtime(true);

        $project = $config->project;

        if (!file_exists($config->projects_root.'/projects/'.$project.'/')) {
            die( "No such project '$project'. Aborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/config.ini')) {
            die("No such config.ini in project '$project'. Aborting\n");
        }

        $begin_time = microtime(true);
        $classes = \Tokenizer\Token::getTokenizers($config->phpversion);

        $log = new \Log('tokenizer', $config->projects_root.'/projects/'.$config->project);
        $log->log( 'Starting time : '.date('r'));

        $client = new Client();

        $regex = array();
        $regex2 = array();
        foreach($classes as $class) {
            $new = "Tokenizer\\$class";
    
            $r = \Tokenizer\Token::getInstance($new, $client, $config->phpversion);
            if ($r === null) {
                display("Ignore $new (Version incompatible)\n");
                // ignore
            } elseif ($new == 'Tokenizer\\FunctioncallArray') {
                $regex[$class] = $r;
            } elseif ($new == 'Tokenizer\\Sequence') {
                $regex[$class] = $r;
            } elseif ($r->checkRemaining()) {
                if (in_array($new, array('Tokenizer\\Phpcodemiddle', 'Tokenizer\\Phpcode', ))) {
                    $regex[$class] = $r;
                } else {
                    $regex[$class] = $r;
                }
            } else {
                display("Ignore $new (No token to process)\n");
            }
        }

        $log->log( "Finished loading classes");

        $server_stat = new \Stats($client);
        $total = \Tokenizer\Token::countTotalToken();
        $count = $total + 1;

        $stats = array('token_in'     => $count, 
                       'token_out'    => 2,
                       'relation_in'  => $server_stat->countRelations(), 
                       'relation_out' => 4,
                       'project'      => $project);
        $log->log('Finished counting Token');

        $extra_rounds = 4;
        $prev = array();
        for($i = 0; $i < $extra_rounds + 1; $i++) {
            $prev[$i] = $count + $i;
        }
        $round = 0;
        $cost = 0;

        $wbegin = microtime(true);
        $regex_time = 0;
        $regex_next = $regex;
        $end = microtime(true);
        $log->log('initialisation : '.(($end - $begin) * 1000));

        while($this->check_prev($prev, $extra_rounds)) {
            $rbegin = microtime(true);
            $round++;
            $log->log("round $round)");
            \Tokenizer\TokenAuto::$round = $round;
    
            array_unshift($prev, $count);

            $regex = $regex_next;
            if ($round == 2) {
                foreach($regex2 as $name => $r) {
                    $regex[$name] = $r;
                }
            }
            $regex_next = array();
            foreach($regex as $name => $r) {
                $begin = microtime(true);
                $r->check();
                if ( ($r->total != 0) || in_array($name , array('FunctioncallArray', 'Sequence'))) {
                    $regex_next[$name] = $r;
                }
                $end = microtime(true);
        
                $regex_time += $end - $begin;

                if ($r->total  > 0) {
                    $ratio =  $r->done / $r->total;
                } else {
                    $ratio = -1;
                }
        
                $log->log( get_class($r)."\t".(($end - $begin) * 1000)."\t".$r->total."\t".$r->done."\t".number_format(100 * $ratio, 0));
            }
            $log->log('Finished foreach');
            unset($precedence);
            \Tokenizer\Token::finishSequence();
    
            $rend = microtime(true);
            $begin = microtime(true);
            $log->log("round : $round\t".(($rend - $rbegin) * 1000));
            $cost += count($regex_next);
            $log->log("cost : $cost");

            if (isset($count)) {
                $count_prev = $count;
                $count = \Tokenizer\Token::countLeftNext();
            } else {
                $count = \Tokenizer\Token::countLeftNext();
                $count_prev = $count;
            }
    
            if (isset($count_file)) {
                $count_file_prev = $count_file;
                $count_file = \Tokenizer\Token::countFileToProcess();
            } else {
                $count_file = \Tokenizer\Token::countFileToProcess();
                $count_file_prev = $count_file;
            }
    
            $end = microtime(true);
            $log->log('countLeftNext time : '.(($end - $begin) * 1000));

            $log->log("Remaining token to process : $count (".($count - $count_prev).')');
            $log->log("Remaining files to process : $count_file (".($count_file - $count_file_prev).')');
            $log->log('Remaining regex : '.count($regex_next).' ('.(count($regex) - count($regex_next)).')');
    
            if ($count > 3) {
                display( "$round) Remains $count of $total tokens to process! \n");
            } else {
                display( "$round) All $total tokens have been processed! \n");
                break 1;
            }
        }

        $wend = microtime(true);
        $log->log("Total while $round)\t".(($wend - $wbegin)*1000));
        $log->log("Total regex time\t".(($regex_time) * 1000));
        $log->log("final cost : $cost");

        $server_stat->collect();
        $stats['token_out'] = $server_stat->tokens_count;
        $stats['relation_out'] = $server_stat->relations_count;

        \Tokenizer\Token::cleanHidden();

        $end_time = microtime(true);
        display('Total time : '.number_format(($end_time - $begin_time) * 1000, 2, '.', ' ')."ms\n");
        // @todo display checks processed
    }

    private function check_prev($prev, $extra_rounds) {
        $b = false;
        for($i = 0; $i < $extra_rounds; $i++) {
            $b = $b || ($prev[$i + 1] > $prev[$i]);
        }
        return $b;
    }
}

?>
