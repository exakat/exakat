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

class Doctor extends Tasks {
    public function __construct($gremlin) {
        $this->enabledLog = false;
        parent::__construct($gremlin);
    }

    public function run(\Config $config) {
        $stats = array();

        $stats = array_merge($stats, $this->checkPreRequisite($config));
        $stats = array_merge($stats, $this->checkAutoInstall($config));
        $stats = array_merge($stats, $this->checkOptional($config));

        $doctor = '';
        foreach($stats as $section => $details) {
            $doctor .= "$section : \n";
            foreach($details as $k => $v) {
                $doctor .= '    '.substr("$k                          ", 0, 20).' : '.$v."\n";
            }
            $doctor .= "\n";
        }
        
        print $doctor;
    }

    private function checkPreRequisite($config) {
// Compulsory
        $stats['exakat']['version'] = \Exakat::VERSION;
        $stats['exakat']['build'] = \Exakat::BUILD;

        // check for PHP
        $stats['PHP']['version']   = phpversion();
        $stats['PHP']['curl']      = extension_loaded('curl')      ? 'Yes' : 'No (Compulsory, please install it)';
        $stats['PHP']['hash']      = extension_loaded('tokenizer') ? 'Yes' : 'No (Compulsory, please install it)';
        $stats['PHP']['sqlite3']   = extension_loaded('sqlite3')   ? 'Yes' : 'No (Compulsory, please install it)';
        $stats['PHP']['tokenizer'] = extension_loaded('tokenizer') ? 'Yes' : 'No (Compulsory, please install it)';
        $stats['PHP']['phar']      = extension_loaded('phar')      ? 'Yes' : 'No (Needed to run exakat.phar)';

        // java
        $res = shell_exec('java -version 2>&1');
        if (preg_match('/command not found/is', $res)) {
            $stats['java']['installed'] = 'No';
            $stats['java']['installation'] = 'No java found. Please, install Java Runtime (SRE) 1.7 or above from java.com web site.';
        } elseif (preg_match('/java version "(.*)"/is', $res, $r)) {
            $lines = explode("\n", $res);
            $line2 = $lines[1];
            $stats['java']['installed'] = 'Yes';
            $stats['java']['type'] = trim($line2);
            $stats['java']['version'] = $r[1];
        } else {
            $stats['java']['error'] = $res;
            $stats['java']['installation'] = 'No java found. Please, install Java Runtime (SRE) 1.7 or above from java.com web site.';
        }
        $res = getenv('JAVA_HOME');
        $stats['java']['$JAVA_HOME'] = $res;

        // neo4j
        if (empty($config->neo4j_folder)) {
            $stats['neo4j']['installed'] = 'Couldn\'t find the path to neo4j from the config/config.ini. Please, check it.';
        } elseif (!file_exists($config->neo4j_folder)) {
            $stats['neo4j']['installed'] = 'No (folder : '.$config->neo4j_folder.')';
        } else {
            $file = file($config->neo4j_folder.'/README.txt');
            $stats['neo4j']['version'] = trim($file[0]);

            if (isset($stats['java']['version']) && version_compare($stats['java']['version'], '1.8') < 0) {
                $file = file_get_contents($config->neo4j_folder.'/conf/neo4j-wrapper.conf');
                if (!preg_match('/wrapper.java.additional=-XX:MaxPermSize=(\d+\w)/is', $file, $r)) {
                    $stats['neo4j']['MaxPermSize'] = 'Unset (64M)';
                    $stats['neo4j']['MaxPermSize warning'] = 'Set MaxPermSize to 512 or more in neo4j/conf/neo4j-wrapper.conf, with "wrapper.java.additional=-XX:MaxPermSize=512m" around line 20';
                } else {
                    $stats['neo4j']['MaxPermSize'] = $r[1];
                }
            }

            $file = file_get_contents($config->neo4j_folder.'/conf/neo4j-server.properties');
            if (preg_match('/org.neo4j.server.webserver.port *= *(\d+)/is', $file, $r)) {
                $stats['neo4j']['port'] = $r[1];
            } else {
                $stats['neo4j']['port'] = '7474 (in fact, unset value. Using default : 7474)';
            }

            if ($stats['neo4j']['port'] != $config->neo4j_port) {
                $stats['neo4j']['port_alert'] = $config->neo4j_port.' : configured port in config/config.ini is not the one in the neo4j installation. Please, sync them.';
            }

            if (preg_match('/dbms.security.auth_enabled\s*=\s*false/is', $file, $r)) {
                $stats['neo4j']['authentication'] = 'Not enabled';
            } else {
                $stats['neo4j']['authentication'] = 'Enabled.';
                if (empty($config->neo4j_login)) {
                    $stats['neo4j']['login'] = 'Login is not set, but authentication is. Please, set login in config/config.ini';
                } elseif (empty($config->neo4j_password)) {
                    $stats['neo4j']['password'] = 'Login is set, but not password. Please, set it in config/config.ini';
                }
                $res = $this->gremlin->query('"Hello world"');
                if ($res === null) {
                    $stats['neo4j']['credentials'] = 'Server is not running.';
                } elseif (isset($res->success) && $res->success === true) {
                    $stats['neo4j']['credentials'] = 'OK.';
                } else {
                    $stats['neo4j']['credentials'] = 'Login or password are wrong (message : '.$res->errors[0]->message.')';
                }
            }

            if (preg_match('#org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp#is', $file, $r)) {
                $stats['neo4j']['gremlinPlugin'] = 'Configured.';
            } else {
                $stats['neo4j']['gremlinPlugin'] = 'Not found. Make sure that "org.neo4j.server.thirdparty_jaxrs_classes=com.thinkaurelius.neo4j.plugins=/tp" is in the conf/neo4j-server.property.';
            }

            $gremlinPlugin = glob($config->neo4j_folder.'/plugins/*/neo4j-gremlin-3.*.jar');
            if (empty($gremlinPlugin)) {
                $stats['neo4j']['gremlinJar'] = 'neo4j-gremlin-3.*.jar coudln\'t be found in the '.$config->neo4j_folder.'/plugins/* folders. Make sure gremlin is installed, and running gremlin version 3.* (3.2 is recommended).';
            } elseif (count($gremlinPlugin) > 1) {
                $stats['neo4j']['gremlinJar'] = 'Found '.count($gremlinPlugin).' plugins gremlin. Only one neo4j-gremlin-3.*.jar is sufficient. ';
            } else {
                $stats['neo4j']['gremlinJar'] = basename(trim(array_pop($gremlinPlugin)));
            }

            $stats['neo4j']['scriptFolder'] = file_exists($config->neo4j_folder.'/scripts/') ? 'Yes' : 'No';
            if ($stats['neo4j']['scriptFolder'] == 'No') {
                mkdir($config->neo4j_folder.'/scripts/', 0755);
                file_put_contents($config->neo4j_folder.'/scripts/exakat.txt', 'This folder and this file were created by exakat.');
                $stats['neo4j']['scriptFolder'] = file_exists($config->neo4j_folder.'/scripts/') ? 'Yes' : 'No';
            }
            $stats['neo4j']['Neo4J for exakat'] = file_exists($config->neo4j_folder.'/scripts/exakat.txt') ? 'Yes' : 'No (Warning : make sure exakat is not trying to access an existing Neo4J database.)';
            
            $pidPath = $config->neo4j_folder.'/conf/neo4j-service.pid';
            if (file_exists($pidPath)) {
                $stats['neo4j']['pid'] = file_get_contents($pidPath);
            } else {
                $res = shell_exec('ps aux | grep gremlin | grep plugin');
                preg_match('/^\w+\s+(\d+)\s/is', $res, $r);
                $stats['neo4j']['pid'] = $r[1];
            }
    
            $json = @file_get_contents('http://'.$config->neo4j_host.':'.$config->neo4j_port.'/db/data/');
            if (empty($json)) {
                $stats['neo4j']['running'] = 'No';
            } else {
                $stats['neo4j']['running'] = 'Yes';
                $status = shell_exec('cd '.$config->neo4j_folder.'; ./bin/neo4j status');
                if (strpos($status, 'Neo4j Server is running at pid') !== false) {
                    $stats['neo4j']['running here'] = 'Yes';
                } else {
                    $stats['neo4j']['running here'] = 'No';
                }
                
                if ('{"success":true}' === file_get_contents('http://'.$config->neo4j_host.':'.$config->neo4j_port.'/tp/gremlin/execute')) {
                    $stats['neo4j']['gremlin'] = 'Yes';
                } else {
                    $stats['neo4j']['gremlin'] = 'No';
                    $stats['neo4j']['gremlin-installation'] = 'Install gremlin plugin for neo4j';
                }
            }
            
            $stats['neo4j']['$NEO4J_HOME'] = getenv('NEO4J_HOME');
            $stats['neo4j']['$NEO4J_HOME / config'] = realpath(getenv('NEO4J_HOME')) === realpath($config->neo4j_folder) ? 'Same' : 'Different';
        }

        return $stats;
    }
    
    private function checkAutoInstall(\Config $config) {
        $stats = array();
        
        // config
        if (!file_exists($config->projects_root.'/config')) {
            mkdir($config->projects_root.'/config', 0755);
        }

        if (!file_exists($config->projects_root.'/config/config.ini')) {
            $version = PHP_MAJOR_VERSION.PHP_MINOR_VERSION;
            
            $neo4j_folder = getenv('NEO4J_HOME');
            if (empty($neo4j_folder)) {
                $neo4j_folder = 'neo4j'; // Local Installation
            } elseif (!file_exists($neo4j_folder)) {
                $neo4j_folder = 'neo4j'; // Local Installation
            } elseif (!file_exists($neo4j_folder.'/scripts/')) {
                // This Neo4J has no 'scripts' folder and we use it! Not our database
                $neo4j_folder = 'neo4j'; // Local Installation
            } elseif (!file_exists($neo4j_folder.'/scripts/exakat.txt')) {
                // This Neo4J has no 'exakat.txt' file : Not an exakat installation! 
                $neo4j_folder = 'neo4j'; // Local Installation
            }
            $ini = <<<INI
; where and which PHP executable are available
neo4j_host     = '127.0.0.1';
neo4j_port     = '7474';
neo4j_folder   = '$neo4j_folder';
neo4j_login    = 'admin';
neo4j_password = 'admin';

; where and which PHP executable are available
php          = {$_SERVER['_']}

;php52        = /path/to/php53
;php53        = /path/to/php53
;php54        = /path/to/php54
;php55        = /path/to/php55
;php56        = /path/to/php56
;php70        = /path/to/php70
;php71        = /path/to/php71
;php72        = /path/to/php72
php$version        = {$_SERVER['_']}

; Limit the size of a project to 1000 k tokens (about 100 k LOC)
token_limit = 1000000
INI;
            file_put_contents($config->projects_root.'/config/config.ini', $ini);
        }
        
        if (!file_exists($config->projects_root.'/config/')) {
            $stats['folders']['config-folder'] = 'No';
        } elseif (file_exists($config->projects_root.'/config/config.ini')) {
            $stats['folders']['config-folder'] = 'Yes';
            $stats['folders']['config.ini'] = 'Yes';

            $ini = parse_ini_file($config->projects_root.'/config/config.ini');
        } else {
            $stats['folders']['config-folder'] = 'Yes';
            $stats['folders']['config.ini'] = 'No';
        }

        // projects
        if (file_exists($config->projects_root.'/projects/')) {
            $stats['folders']['projects folder'] = 'Yes';
        } else {
            mkdir($config->projects_root.'/projects/', 0755);
            if (file_exists($config->projects_root.'/projects/')) {
                $stats['folders']['projects folder'] = 'Yes';
            } else {
            $stats['folders']['projects folder'] = 'No';
            }
        }

        $stats['folders']['progress'] = file_exists($config->projects_root.'/progress/') ? 'Yes' : 'No';
        if ($stats['folders']['progress'] == 'No') {
            mkdir($config->projects_root.'/progress/', 0755);
            file_put_contents($config->projects_root.'/progress/jobqueue.exakat', '{"progress":"17"}');
            $stats['folders']['progress'] = file_exists($config->projects_root.'/progress/') ? 'Yes' : 'No';
        }

        $stats['folders']['in'] = file_exists($config->projects_root.'/in/') ? 'Yes' : 'No';
        $stats['folders']['out'] = file_exists($config->projects_root.'/out/') ? 'Yes' : 'No';
        $stats['folders']['projects/test'] = file_exists($config->projects_root.'/projects/test/') ? 'Yes' : 'No';
        $stats['folders']['projects/default'] = file_exists($config->projects_root.'/projects/default/') ? 'Yes' : 'No';
        $stats['folders']['projects/onepage'] = file_exists($config->projects_root.'/projects/onepage/') ? 'Yes' : 'No';

        return $stats;
    }

    private function checkOptional(\Config $config) {
        $stats = array();

        // check PHP 5.2
        if (!$config->php52) {
            $stats['PHP 5.2']['configured'] = 'No';
        } else {
            $version = trim(shell_exec($config->php52.' -r "echo phpversion();" 2>&1'));
            if (strpos($version, 'not found') !== false) {
                $stats['PHP 5.2']['installed'] = 'No';
            } else {
                $stats['PHP 5.2']['installed'] = 'Yes';
                if (substr($version, 0, 3) != '5.3') {
                    $stats['PHP 5.3']['version'] = $version.' (This doesn\'t seem to be version 5.3)';
                }
                $stats['PHP 5.2']['short_open_tags'] = shell_exec($config->php52.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 5.2']['timezone'] = shell_exec($config->php52.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 5.2']['tokenizer'] = shell_exec($config->php52.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
                $stats['PHP 5.2']['memory_limit'] = shell_exec($config->php52.' -r "echo ini_get(\'memory_limit\');" 2>&1');
            }
        }

        // check PHP 5.3
        if (!$config->php53) {
            $stats['PHP 5.3']['configured'] = 'No';
        } else {
            $stats['PHP 5.3']['configured'] = 'Yes';
            $version = trim(shell_exec($config->php53.' -r "echo phpversion();" 2>&1'));
            if (strpos($version, 'not found') !== false) {
                $stats['PHP 5.3']['installed'] = 'No';
            } elseif (strpos($version, 'No such file') !== false) {
                $stats['PHP 5.3']['installed'] = 'No';
            } else {
                $stats['PHP 5.3']['installed'] = 'Yes';
                $stats['PHP 5.3']['version'] = $version;
                if (substr($version, 0, 3) != '5.3') {
                    $stats['PHP 5.3']['version'] = $version.' (This doesn\'t seem to be version 5.3)';
                }
                $stats['PHP 5.3']['short_open_tags'] = shell_exec($config->php53.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 5.3']['timezone'] = shell_exec($config->php53.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 5.3']['tokenizer'] = shell_exec($config->php53.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
                $stats['PHP 5.3']['memory_limit'] = shell_exec($config->php53.' -r "echo ini_get(\'memory_limit\');" 2>&1');
            }
        }
        
        // check PHP 5.4
        if (!$config->php54) {
            $stats['PHP 5.4']['configured'] = 'No';
        } else {
            $stats['PHP 5.4']['configured'] = 'Yes';
            $version = trim(shell_exec($config->php54.' -r "echo phpversion();" 2>&1'));
            if (strpos($version, 'not found') !== false) {
                $stats['PHP 5.4']['installed'] = 'No';
            } elseif (strpos($version, 'No such file') !== false) {
                $stats['PHP 5.4']['installed'] = 'No';
            } else {
                $stats['PHP 5.4']['installed'] = 'Yes';
                $stats['PHP 5.4']['version'] = $version;
                if (substr($version, 0, 3) != '5.4') {
                    $stats['PHP 5.4']['version'] = $version.' (This doesn\'t seem to be version 5.4)';
                }
                $stats['PHP 5.4']['short_open_tags'] = shell_exec($config->php54.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 5.4']['timezone'] = shell_exec($config->php54.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 5.4']['tokenizer'] = shell_exec($config->php54.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
                $stats['PHP 5.4']['memory_limit'] = shell_exec($config->php54.' -r "echo ini_get(\'memory_limit\');" 2>&1');
            }
        }
        
        // check PHP 5.5
        if (!$config->php55) {
            $stats['PHP 5.5']['configured'] = 'No';
        } else {
            $stats['PHP 5.5']['configured'] = 'Yes';
            $version = trim(shell_exec($config->php55.' -r "echo phpversion();" 2>&1'));
            if (strpos($version, 'not found') !== false) {
                $stats['PHP 5.5']['installed'] = 'No';
            } elseif (strpos($version, 'No such file') !== false) {
                $stats['PHP 5.5']['installed'] = 'No';
            } else {
                $stats['PHP 5.5']['installed'] = 'Yes';
                $stats['PHP 5.5']['version'] = $version;
                if (substr($version, 0, 3) != '5.5') {
                    $stats['PHP 5.5']['version'] = $version.' (This doesn\'t seem to be version 5.5)';
                }
                $stats['PHP 5.5']['short_open_tags'] = trim(shell_exec($config->php55.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1'));
                $stats['PHP 5.5']['timezone'] = trim(shell_exec($config->php55.' -r "echo ini_get(\'date.timezone\');" 2>&1'));
                $stats['PHP 5.5']['tokenizer'] = shell_exec($config->php55.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
                $stats['PHP 5.5']['memory_limit'] = shell_exec($config->php55.' -r "echo ini_get(\'memory_limit\');" 2>&1');
            }
        }
        
        // check PHP 5.6
        if (!$config->php56) {
            $stats['PHP 5.6']['configured'] = 'No';
        } else {
            $stats['PHP 5.6']['configured'] = $config->php56;
            $version = trim(shell_exec($config->php56.' -r "echo phpversion();" 2>&1'));
            if (strpos($version, 'not found') !== false) {
                $stats['PHP 5.6']['installed'] = 'No';
            } elseif (strpos($version, 'No such file') !== false) {
                $stats['PHP 5.6']['installed'] = 'No';
            } else {
                $stats['PHP 5.6']['installed'] = 'Yes';
                $stats['PHP 5.6']['version'] = $version;
                if (substr($version, 0, 3) != '5.6') {
                    $stats['PHP 5.6']['version'] = $version.' (This doesn\'t seem to be version 5.6)';
                }
                $stats['PHP 5.6']['short_open_tags'] = shell_exec($config->php56.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 5.6']['timezone'] = shell_exec($config->php56.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 5.6']['tokenizer'] = shell_exec($config->php56.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
                $stats['PHP 5.6']['memory_limit'] = shell_exec($config->php56.' -r "echo ini_get(\'memory_limit\');" 2>&1');
            }
        }
        
        // check PHP 7
        if (!$config->php70) {
            $stats['PHP 7.0']['configured'] = 'No';
        } else {
            $stats['PHP 7.0']['configured'] = 'Yes';
            $version = shell_exec($config->php70.' -r "echo phpversion();" 2>&1');
            if (strpos($version, 'not found') !== false) {
                $stats['PHP 7.0']['installed'] = 'No';
            } elseif (strpos($version, 'No such file') !== false) {
                $stats['PHP 7.0']['installed'] = 'No';
            } else {
                $stats['PHP 7.0']['version'] = $version;
                if (substr($version, 0, 3) != '7.0') {
                    $stats['PHP 7.0']['version'] = $version.' (This doesn\'t seem to be version 7.0)';
                }
                $stats['PHP 7.0']['short_open_tags'] = shell_exec($config->php70.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 7.0']['timezone'] = shell_exec($config->php70.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 7.0']['tokenizer'] = shell_exec($config->php70.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
                $stats['PHP 7.0']['memory_limit'] = shell_exec($config->php70.' -r "echo ini_get(\'memory_limit\');" 2>&1');
            }
        }

        // check PHP 7.1
        if (!$config->php71) {
            $stats['PHP 7.1']['configured'] = 'No';
        } else {
            $stats['PHP 7.1']['configured'] = 'Yes';
            $version = shell_exec($config->php71.' -r "echo phpversion();" 2>&1');
            if (strpos($version, 'not found') !== false) {
                $stats['PHP 7.1']['installed'] = 'No';
            } elseif (strpos($version, 'No such file') !== false) {
                $stats['PHP 7.1']['installed'] = 'No';
            } else {
                $stats['PHP 7.1']['version'] = $version;
                if (substr($version, 0, 3) != '7.1') {
                    $stats['PHP 7.1']['version'] = $version.' (This doesn\'t seem to be version 7.1)';
                }
                $stats['PHP 7.1']['short_open_tags'] = shell_exec($config->php71.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 7.1']['timezone'] = shell_exec($config->php71.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 7.1']['tokenizer'] = shell_exec($config->php71.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
                $stats['PHP 7.1']['memory_limit'] = shell_exec($config->php71.' -r "echo ini_get(\'memory_limit\');" 2>&1');
            }
        }

        // check PHP 7.2
        if (!$config->php72) {
            $stats['PHP 7.2']['configured'] = 'No';
        } else {
            $stats['PHP 7.2']['configured'] = 'Yes';
            $version = shell_exec($config->php72.' -r "echo phpversion();" 2>&1');
            if (strpos($version, 'not found') !== false) {
                $stats['PHP 7.2']['installed'] = 'No';
            } elseif (strpos($version, 'No such file') !== false) {
                $stats['PHP 7.2']['installed'] = 'No';
            } else {
                $stats['PHP 7.2']['version'] = $version;
                if (substr($version, 0, 3) != '7.2') {
                    $stats['PHP 7.2']['version'] = $version.' (This doesn\'t seem to be version 7.2)';
                }
                $stats['PHP 7.2']['short_open_tags'] = shell_exec($config->php72.' -r "echo ini_get(\'short_open_tags\') ? \'On (Should be Off)\' : \'Off\';" 2>&1');
                $stats['PHP 7.2']['timezone'] = shell_exec($config->php72.' -r "echo ini_get(\'date.timezone\');" 2>&1');
                $stats['PHP 7.2']['tokenizer'] = shell_exec($config->php72.' -r "echo extension_loaded(\'tokenizer\') ? \'Yes\' : \'No\';" 2>&1');
                $stats['PHP 7.2']['memory_limit'] = shell_exec($config->php72.' -r "echo ini_get(\'memory_limit\');" 2>&1');
            }
        }
        
        // git
        $res = trim(shell_exec('git --version 2>&1'));
        if (preg_match('/git version ([0-9\.]+)/', $res, $r)) {//
            $stats['git']['installed'] = 'Yes';
            $stats['git']['version'] = $r[1];
        } else {
            $stats['git']['installed'] = 'No';
            $stats['git']['optional'] = 'Yes';
        }

        // hg
        $res = trim(shell_exec('hg --version 2>&1'));
        if (preg_match('/Mercurial Distributed SCM \(version ([0-9\.]+)\)/', $res, $r)) {//
            $stats['hg']['installed'] = 'Yes';
            $stats['hg']['version'] = $r[1];
        } else {
            $stats['hg']['installed'] = 'No';
            $stats['hg']['optional'] = 'Yes';
        }

        // svn
        $res = trim(shell_exec('svn --version 2>&1'));
        if (preg_match('/svn, version ([0-9\.]+) /', $res, $r)) {//
            $stats['svn']['installed'] = 'Yes';
            $stats['svn']['version'] = $r[1];
        } else {
            $stats['svn']['installed'] = 'No';
            $stats['svn']['optional'] = 'Yes';
        }

        // bazaar
        $res = trim(shell_exec('bzr --version 2>&1'));
        if (preg_match('/Bazaar \(bzr\) ([0-9\.]+) /', $res, $r)) {//
            $stats['bzr']['installed'] = 'Yes';
            $stats['bzr']['version'] = $r[1];
        } else {
            $stats['bzr']['installed'] = 'No';
            $stats['bzr']['optional'] = 'Yes';
        }

        // composer
        $res = trim(shell_exec('composer about --version 2>&1'));
        // remove colors from shell syntax
        $res = preg_replace('/\e\[[\d;]*m/', '', $res);
        if (preg_match('/ version ([0-9\.a-z\-]+)/', $res, $r)) {//
            $stats['composer']['installed'] = 'Yes';
            $stats['composer']['version'] = $r[1];
        } else {
            $stats['composer']['installed'] = 'No';
        }

        // wget
        $res = explode("\n", shell_exec('wget -V 2>&1'))[0];
        if ($res !== '') {//
            $stats['wget']['installed'] = 'Yes';
            $stats['wget']['version'] = $res;
        } else {
            $stats['wget']['installed'] = 'No';
        }

        // zip
        $res = shell_exec('zip -v  2>&1');
        if (preg_match('/not found/is', $res)) {
            $stats['zip']['installed'] = 'No';
        } elseif (preg_match('/Zip\s+([0-9\.]+)/is', $res, $r)) {
            $stats['zip']['installed'] = 'Yes';
            $stats['zip']['version'] = $r[1];
        } else {
            $stats['zip']['error'] = $res;
        }

        return $stats;
    }
}

?>
