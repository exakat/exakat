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

class Status extends Tasks {
    public function run(\Config $config) {
        $project = $config->project;

        if ($project === 'default') {
            die("Please, provide a project name with -p option. Aborting\n");
        }

        $path = $config->projects_root.'/projects/'.$project;
        
        if (!file_exists($path.'/')) {
            throw new Exakat\Exceptions\NoSuchProject($project);
        }

        $status = array('project' => $project);
        $status['loc'] = $this->datastore->getHash('loc');
        $status['tokens'] = $this->datastore->getHash('tokens');
        $status['status'] = $this->datastore->getHash('status');

        switch($config->project_vcs) {
            case 'git' :
                if (file_exists($config->projects_root.'/projects/'.$config->project.'/code/')) {
                    $status['git status'] = trim(shell_exec('cd '.$config->projects_root.'/projects/'.$config->project.'/code/; git rev-parse HEAD'));
                }
                
                if (file_exists($config->projects_root.'/projects/'.$config->project.'/code/')) {
                    $res = shell_exec('cd '.$config->projects_root.'/projects/'.$config->project.'/code/; git remote update; git status -uno | grep \'up-to-date\'');
                    $status['updatable'] = empty($res);
                } else {
                    $status['updatable'] = false;
                }
                break 1;

            case 'composer' :
                $json = @json_decode(@file_get_contents($config->projects_root.'/projects/'.$config->project.'/code/composer.lock'));
                if (isset($json->hash)) {
                    $status['hash'] = $json->hash;
                } else {
                    $status['hash'] = 'Can\'t read hash';
                }
                
                $res = shell_exec('cd '.$config->projects_root.'/projects/'.$config->project.'; git remote update; git status -uno | grep \'Nothing to install or update\'');
                $status['updatable'] = empty($res);
                break 1;

            case 'copy' :
            case 'symlink' :
                $status['hash'] = 'None';
                $status['updatable'] = false;
                break 1;

            default:
                $status['hash'] = 'None';
                $status['updatable'] = 'N/A';
                break 1;
        }

        $configuration = array();
        // 

        // Check the logs
        $errors = $this->getErrors($path);
        if (!empty($errors)) {
            $status['errors'] = $errors;
        }
        

        // Status of progress
        // errors? 

        $formats = array();
        foreach(\Reports\Reports::FORMATS as $format) {
            $a = $this->datastore->getHash($format);
            if (!empty($a)) {
                $formats[$format] = $a;
            }
        }
        if (!empty($formats)) {
            $status['formats'] = $formats;
        }

        // Publication : Json or Text file
        if ($config->json == true) {
            print json_encode($status);
        } else {
            $text = '';
            $size = 0;
            foreach($status as $k => $v) {
                $size = max($size, strlen($k));
            }

            foreach($status as $field => $value) {
                if (is_array($value)) {
                    $sub = substr($field.str_repeat(' ', $size), 0, $size)." : \n";

                    $sizea = 0;
                    foreach($value as $k => $v) {
                        $sizea = max($sizea, strlen($k));
                    }
                    foreach($value as $k => $v) {
                        $sub .= "    ".substr($k.str_repeat(' ', $sizea), 0, $sizea)." : $v\n";
                    }
                    $text .= "\n".$sub."\n";
                } else {
                    $text .= substr($field.str_repeat(' ', $size), 0, $size) . ' : '.$value."\n";
                }
            }
            
            print $text;
        }
    }
    
    private function getErrors($path) {
        $errors = array();

        // Init error
        $e = $this->datastore->getHash('init error');
        if (!empty($e)) {
            $errors['init error'] = $e;
            return $errors;
        }
        
        // Size error
        $e = $this->datastore->getHash('token error');
        if (!empty($e)) {
            $errors['init error'] = $e;
            return $errors;
        }
        
        // Error log
        if (!file_exists($path.'/log/errors.log')) {
            $errors['errors.log'] = 'errors.log is missing';
        } elseif (filesize($path.'/log/errors.log') != 191) {
            $log = file_get_contents($path.'/log/errors.log');
            preg_match_all("#files with next : (.+?)\n((  .*?\n)*)#m", $log, $r);
            $errors['errors.log'] = 'errors.log has '.(count($r[1]) + count(explode("\n", $r[2][0]))).' files in error';
        } // Else no report

        // Tokenizer log
        if (!file_exists($path.'/log/errors.log')) {
            $errors['errors.log'] = 'errors.log is missing';
        } elseif (filesize($path.'/log/errors.log') != 191) {
            $log = file_get_contents($path.'/log/errors.log');
            if (preg_match_all("#files with next : (.+?)\n((  .*?\n)*)#m", $log, $r)) {
                $errors['errors.log'] = 'errors.log has '.(count($r[1]) + count(explode("\n", $r[2][0]))).' files in error';
            }
        } // Else no report        
        if (!empty($errors)) {
            $status['errors'] = $errors;
        }
        
        return $errors;
    }
}

?>
