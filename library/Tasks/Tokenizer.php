<?php
/*
 * Copyright 2012-2016 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

class Tokenizer extends Tasks {
    const EXTRA_ROUNDS = 2;

    public function run(\Config $config) {
        $begin = microtime(true);

        $project = $config->project;

        if (!file_exists($config->projects_root.'/projects/'.$project.'/')) {
            die( "No such project '$project'. Aborting\n");
        }

        if (!file_exists($config->projects_root.'/projects/'.$project.'/config.ini')) {
            die("No such config.ini in project '$project'. Aborting\n");
        }
        
        $this->checkTokenLimit();

        $begin_time = microtime(true);
        $classes = \Tokenizer\Token::getTokenizers($config->phpversion);

        $this->log->log( 'Starting time : '.date('r'));
        
        $datastore = new \Datastore($config);
        $tokenCounts = $datastore->getCol('tokenCounts', 'token');

        $regex = array();
        $regex2 = array();
        foreach($classes as $class) {
            $new = "Tokenizer\\$class";
            $r = \Tokenizer\Token::getInstance($new, $this->gremlin, $config->phpversion);
            $regex[$class] = $r;
        }

        $this->log->log( "Finished loading classes");

        $server_stat = new \Stats($this->gremlin);
        \Tokenizer\Token::$staticGremlin = $this->gremlin; // This initiate static::$gremlin
        $total = \Tokenizer\Token::countTotalToken();
        $count = $total + 1;

        $stats = array('token_in'     => $count,
                       'token_out'    => 2,
                       'relation_in'  => $server_stat->countRelations(),
                       'relation_out' => 4,
                       'project'      => $project);
        $this->log->log('Finished counting Token');

        $prev = array();
        for($i = 0; $i < self::EXTRA_ROUNDS + 1; ++$i) {
            $prev[$i] = $count + $i;
        }
        $round = 0;
        $cost = 0;

        $wbegin = microtime(true);
        $regex_time = 0;
        $regex_next = $regex;
        $end = microtime(true);
        $this->log->log('initialisation : '.(($end - $begin) * 1000));

        while($this->check_prev($prev, self::EXTRA_ROUNDS)) {
            $rbegin = microtime(true);
            ++$round;
            $this->log->log("round $round)");
    
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
        
                $this->log->log( get_class($r)."\t".(($end - $begin) * 1000)."\t".$r->total."\t".$r->done."\t".$r->cycles."\t".number_format(100 * $ratio, 0));
            }
            $this->log->log('Finished foreach');
            \Tokenizer\Token::finishSequence();
    
            $rend = microtime(true);
            $begin = microtime(true);
            $this->log->log("round : $round\t".(($rend - $rbegin) * 1000));
            $cost += count($regex_next);
            $this->log->log("cost : $cost");

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
            $this->log->log('countLeftNext time : '.(($end - $begin) * 1000));

            $this->log->log("Remaining token to process : $count (".($count - $count_prev).')');
            $this->log->log("Remaining files to process : $count_file (".($count_file - $count_file_prev).')');
            $this->log->log('Remaining regex : '.count($regex_next).' ('.(count($regex) - count($regex_next)).')');
            
//            if ($round == 3) { die('Round '.$round);}
            if ($count > 3) {
                display( "$round) Remains $count of $total tokens to process! \n");
            } else {
                display( "$round) All $total tokens have been processed! \n");
                break 1;
            }
        }

        $wend = microtime(true);
        $this->log->log("Total while $round)\t".(($wend - $wbegin)*1000));
        $this->log->log("Total regex time\t".(($regex_time) * 1000));
        $this->log->log("final cost : $cost");

//        $server_stat->collect();
        $stats['token_out'] = $server_stat->tokens_count;
        $stats['relation_out'] = $server_stat->relations_count;
        \Tokenizer\Token::cleanHidden();

        $end_time = microtime(true);
        display('Total time : '.number_format(($end_time - $begin_time) * 1000, 2, '.', ' ')."ms\n");

        $this->datastore->addRow('hash', array('status' => 'Tokenizer'));
        // @todo display checks processed
    }

    private function check_prev($prev, $extra_rounds) {
        $b = false;
        for($i = 0; $i < $extra_rounds; ++$i) {
            $b = $b || ($prev[$i + 1] > $prev[$i]);
        }
        return $b;
    }
}

?>
