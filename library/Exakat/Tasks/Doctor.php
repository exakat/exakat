<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
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

use Exakat\Exakat;
use Exakat\Graph\Graph;
use Exakat\Config;
use Exakat\Phpexec;
use Exakat\Tasks\Helpers\Php;
use Exakat\Exceptions\NoPhpBinary;

class Doctor extends Tasks {
    const CONCURENCE = self::ANYTIME;

    protected $logname = self::LOG_NONE;

    public function __construct(Graph $gremlin, Config $config, $subTask = self::IS_NOT_SUBTASK) {
        $this->config = $config;
        // Ignoring everything
    }

    public function run() {
        $stats = array_merge($this->checkPreRequisite(),
                             $this->checkAutoInstall());

        $phpBinaries = array('php'.str_replace('.', '', substr(PHP_VERSION, 0, 3)) => PHP_BINARY);
        foreach(Config::PHP_VERSIONS as $shortVersion) {
            $configName = "php$shortVersion";
            if (!empty($this->config->$configName)) {
                $phpBinaries[$configName] = $this->config->$configName;
            }
        }

        $stats = array_merge($stats,
                             $this->checkPHPs($phpBinaries));
        
        if ($this->config->verbose === true) {
            $stats = array_merge($stats, $this->checkOptional());
        }

        if ($this->config->json === true) {
            print json_encode($stats);
            return;
        }

        $doctor = '';
        foreach($stats as $section => $details) {
            $doctor .= "$section : ".PHP_EOL;
            foreach($details as $k => $v) {
                $doctor .= '    '.substr("$k                          ", 0, 20).' : '.$v.PHP_EOL;
            }
            $doctor .= PHP_EOL;
        }
        print $doctor;
    }

    private function checkPreRequisite() {
        // Compulsory
        $stats['exakat']['executable']  = $this->config->executable;
        $stats['exakat']['version']     = Exakat::VERSION;
        $stats['exakat']['build']       = Exakat::BUILD;
        $stats['exakat']['exakat.ini']  = $this->array2list($this->config->configFiles);
        $stats['exakat']['graphdb']     = $this->config->graphdb;
        $reportList = array();
        foreach($this->config->project_reports as $project_report) {
            $className = "\\Exakat\\Reports\\$project_report";
            if (class_exists($className)) {
                $reportList[] = $project_report;
            }
        }
        sort($reportList);
        $stats['exakat']['reports']      = $this->array2list($reportList);
        
        $stats['exakat']['themes']       = $this->array2list($this->config->project_themes);
        $stats['exakat']['extra themes'] = $this->array2list(array_keys($this->config->themas));

        $stats['exakat']['tokenslimit'] = number_format((int) $this->config->token_limit, 0, '', ' ');
        if ($list = $this->config->ext->getJarList()) {
            $stats['exakat']['extensions']  = $this->array2list($list);
        }

        // check for PHP
        $stats['PHP']['binary']         = phpversion();
        $stats['PHP']['memory_limit']   = ini_get('memory_limit');
        $stats['PHP']['ext/curl']       = extension_loaded('curl')      ? 'Yes' : 'No (Compulsory, please install it with --with-curl)';
        $stats['PHP']['ext/hash']       = extension_loaded('hash')      ? 'Yes' : 'No (Compulsory, please install it with --enable-hash)';
        $stats['PHP']['ext/phar']       = extension_loaded('phar')      ? 'Yes' : 'No (Needed to run exakat.phar. please install by default)';
        $stats['PHP']['ext/sqlite3']    = extension_loaded('sqlite3')   ? 'Yes' : 'No (Compulsory, please install it by default (remove --without-sqlite3))';
        $stats['PHP']['ext/tokenizer']  = extension_loaded('tokenizer') ? 'Yes' : 'No (Compulsory, please install it by default (remove --disable-tokenizer))';
        $stats['PHP']['ext/mbstring']   = extension_loaded('mbstring')  ? 'Yes' : 'No (Compulsory, add --enable-mbstring to configure)';
        $stats['PHP']['ext/json']       = extension_loaded('json')      ? 'Yes' : 'No';
        $stats['PHP']['ext/xmlwriter']  = extension_loaded('xmlwriter') ? 'Yes' : 'No (Optional, used by XML reports)';

        // java
        $res = shell_exec('java -version 2>&1');
        if (stripos($res, 'command not found') !== false) {
            $stats['java']['installed'] = 'No';
            $stats['java']['installation'] = 'No java found. Please, install Java Runtime (SRE) 1.7 or above from java.com web site.';
        } elseif (preg_match('/(java|openjdk) version "(.*)"/is', $res, $r)) {
            $lines = explode(PHP_EOL, $res);
            $line2 = $lines[1];
            $stats['java']['installed'] = 'Yes';
            $stats['java']['type'] = trim($line2);
            $stats['java']['version'] = $r[1];
        } else {
            $stats['java']['error'] = $res;
            $stats['java']['installation'] = 'No java found. Please, install Java Runtime (SRE) 1.7 or above from java.com web site.';
        }
        $stats['java']['$JAVA_HOME'] = getenv('JAVA_HOME') ?? '<none>';
        $stats['java']['$JAVA_OPTIONS'] = getenv('JAVA_OPTIONS') ?? ' (set $JAVA_OPTIONS="-Xms32m -Xmx****m", with **** = RAM in Mb. The more the better.';

        $stats['tinkergraph'] = $this->getTinkerGraph();
        $stats['gsneo4j'] = $this->getTinkerGraphNeo4j();

        if ($this->config->project !== 'default') {
            $stats['project']['name']             = $this->config->project_name;
            $stats['project']['url']              = $this->config->project_url;
            $stats['project']['included dirs']    = makeList($this->config->include_dirs, '');
            $stats['project']['ignored dirs']     = makeList($this->config->ignore_dirs, '');
            $stats['project']['file extensions']  = makeList($this->config->file_extensions, '');
            $stats['project']['analyzers']        = makeList($this->config->analyzers, '');
        }

        return $stats;
    }

    private function checkAutoInstall() {
        $stats = array();

        // config
        if (!file_exists("{$this->config->projects_root}/config")) {
            mkdir("{$this->config->projects_root}/config", 0755);
        }

        if (file_exists("{$this->config->projects_root}/config/exakat.ini")) {
            $graphdb = $this->config->graphdb;
        } else {
            $ini = file_get_contents("{$this->config->dir_root}/server/exakat.ini");
            $version = PHP_MAJOR_VERSION.PHP_MINOR_VERSION;
            
            if (file_exists("{$this->config->projects_root}/tinkergraph")) {
                $folder = 'tinkergraph';
                // tinkergraph or gsneo4j
                if (file_exists("{$this->config->projects_root}/tinkergraph/ext/neo4j-gremlin/")) {
                    $graphdb = 'gsneo4j';
                } else {
                    $graphdb = 'tinkergraph';
                }
            } else {
                $graphdb = 'NONE';
            }

            $ini = str_replace(array('{VERSION}', '{VERSION_PATH}',   '{GRAPHDB}', ";$graphdb", '{GRAPHDB}_path', ),
                               array( $version,    $this->config->php, $graphdb,    $graphdb,    $folder),
                               $ini);
            
            file_put_contents("{$this->config->projects_root}/config/exakat.ini", $ini);
        }
        
        $this->checkInstall($graphdb);

        // projects
        if (file_exists("{$this->config->projects_root}/projects/")) {
            $stats['folders']['projects folder'] = 'Yes';
        } else {
            mkdir("{$this->config->projects_root}/projects/", 0755);
            if (file_exists("{$this->config->projects_root}/projects/")) {
                $stats['folders']['projects folder'] = 'Yes';
            } else {
                $stats['folders']['projects folder'] = 'No';
            }
        }

        $stats['folders']['projects/test']    = file_exists($this->config->projects_root.'/projects/test/') ? 'Yes' : 'No';
        $stats['folders']['projects/default'] = file_exists($this->config->projects_root.'/projects/default/') ? 'Yes' : 'No';
        $stats['folders']['projects/onepage'] = file_exists($this->config->projects_root.'/projects/onepage/') ? 'Yes' : 'No';

        return $stats;
    }
    
    private function checkInstall($graphdb) {
        if ($graphdb === 'gsneo4j') {
            $properties = file_get_contents("{$this->config->projects_root}/tinkergraph/conf/neo4j-empty.properties");
            $properties = preg_replace("#gremlin.neo4j.directory=.*\n#s", "gremlin.neo4j.directory=db/neo4j\n", $properties);
            file_put_contents("{$this->config->projects_root}/tinkergraph/conf/neo4j-empty.properties", $properties);

            if (!file_exists("{$this->config->projects_root}/tinkergraph/bin/gremlin-server.exakat.sh")) {
                if (copy("{$this->config->dir_root}/server/gsneo4j/gremlin-server.sh",
                         "{$this->config->projects_root}/tinkergraph/bin/gremlin-server.exakat.sh")) {
                    chmod("{$this->config->projects_root}/tinkergraph/bin/gremlin-server.exakat.sh", 0755);
                } else {
                    display('Error while copying gremlin-server.exakat.sh file to tinkergraph.');
                }
            }
            $this->checkGremlinServer();
        } elseif ($graphdb === 'tinkergraph') {
            if (!file_exists("{$this->config->projects_root}/tinkergraph/bin/gremlin-server.exakat.sh")) {
                if (copy($this->config->dir_root.'/server/tinkergraph/gremlin-server.sh',
                  $this->config->projects_root.'/tinkergraph/bin/gremlin-server.exakat.sh')) {
                    chmod($this->config->projects_root.'/tinkergraph/bin/gremlin-server.exakat.sh', 0755);
                } else {
                    display('Error while copying gremlin-server.exakat.sh file to tinkergraph.');
                }
            }
            $this->checkGremlinServer();
        } else {
            assert(false, "Checking install with unknown graphdb : '$graphdb'");
        }
    }
    
    private function checkGremlinServer() {
        if (!file_exists("{$this->config->projects_root}/tinkergraph/db")) {
            mkdir("{$this->config->projects_root}/tinkergraph/db", 0755);
        }
    
        $gremlinJar = glob("{$this->config->gsneo4j_folder}/lib/gremlin-core-*.jar");
        $gremlinVersion = basename(array_pop($gremlinJar));
        //gremlin-core-3.2.5.jar
        $gremlinVersion = substr($gremlinVersion, 13, -4);
        $version = version_compare('3.3.0', $gremlinVersion) ? '.3.2' : '.3.3';
    
        if (!copy("{$this->config->dir_root}/server/tinkergraph/tinkergraph{$version}.yaml",
             "{$this->config->projects_root}/tinkergraph/conf/gsneo4j.yaml")) {
            display("Error while copying gsneo4j{$version}.yaml config file to tinkergraph.");
        }
        if (!copy("{$this->config->dir_root}/server/tinkergraph/tinkergraph{$version}.yaml",
             "{$this->config->projects_root}/tinkergraph/tinkergraph.yaml")) {
            display("Error while copying tinkergraph{$version}.yaml config file to tinkergraph.");
        }
    }

    private function checkPHPs($config) {
        $stats = array();

        foreach(Config::PHP_VERSIONS as $shortVersion) {
            $configVersion = "php$shortVersion";
            $version = "$shortVersion[0].$shortVersion[1]";
            if (isset($config[$configVersion])) {
                $stats[$configVersion] = $this->checkPHP($config[$configVersion], $version);
            } else {
                $stats[$configVersion] = array('configured' => 'No');
            }
        }

        return $stats;
    }

    private function checkOptional() {
        $stats = array();

        $optionals = array('Git'       => 'git',
                           'Mercurial' => 'hg',
                           'Svn'       => 'svn',
                           'Bazaar'    => 'bzr',
                           'Composer'  => 'composer',
                           'Zip'       => 'zip',
                           'Rar'       => 'rar',
                           'Tarbz'     => 'tbz',
                           'Targz'     => 'tgz',
                          );

        foreach($optionals as $class => $section) {
            $fullClass = "\Exakat\Vcs\\$class";
            $vcs = new $fullClass($this->config->project, $this->config->projects_root);
            $stats[$section] = $vcs->getInstallationInfo();
        }

        return $stats;
    }

    private function checkPHP($pathToBinary, $displayedVersion) {
        $stats = array();
        
        $version = 'php'.str_replace('.', '', $displayedVersion);
        
        $stats['configured'] = 'Yes ('.$pathToBinary.')';

        try {
            $php = new Phpexec($displayedVersion, $pathToBinary);
        } catch (NoPhpBinary $e) {
            $stats['installed'] = 'Invalid path : '.$pathToBinary;
        } finally {
            return $stats;
        }

        $version = $php->getConfiguration('phpversion');
        if (strpos($version, 'not found') !== false) {
            $stats['installed'] = 'No';
        } elseif (strpos($version, 'No such file') !== false) {
            $stats['installed'] = 'No';
        } else {
            $stats['version']         = $php->getConfiguration('phpversion');
            $stats['short_open_tags'] = $php->getConfiguration('short_open_tags') ? 'Yes' : 'No';
            $stats['tokenizer']       = $php->getConfiguration('tokenizer') ? 'Yes' : 'No';
            $stats['assertions']      = $php->getConfiguration('assertions') ? 'Yes' : 'No';
            $stats['memory_limit']    = $php->getConfiguration('memory_limit');
            $stats['timezone']        = $php->getConfiguration('timezone') ? 'None' : $php->getConfiguration('timezone');

            if (substr($version, 0, 3) != $displayedVersion) {
                $stats['version'] = $version.' (This doesn\'t seem to be version '.$displayedVersion.')';
            }

            $tokens = $php->getTokens();
            $stats['token_version'] = substr(get_class(Php::getInstance($tokens)), -5);
        }

        return $stats;
    }

    private function array2list(array $array) {
        return implode(",\n                           ", $array);
    }

    private function getTinkerGraph() {
        $stats = array();

        if (empty($this->config->tinkergraph_folder)) {
            $stats['configured'] = 'No tinkergraph configured in config/exakat.ini.';
        } elseif (!file_exists($this->config->tinkergraph_folder)) {
            $stats['installed'] = 'No (folder : '.$this->config->tinkergraph_folder.')';
        } else {
            $stats['installed'] = 'Yes (folder : '.$this->config->tinkergraph_folder.')';
            $stats['host'] = $this->config->tinkergraph_host;
            $stats['port'] = $this->config->tinkergraph_port;

            $gremlinJar = glob($this->config->tinkergraph_folder.'/lib/gremlin-core-*.jar');
            $gremlinVersion = basename(array_pop($gremlinJar));
            //gremlin-core-3.2.5.jar
            $gremlinVersion = substr($gremlinVersion, 13, -4);
            
            $stats['gremlin version'] = $gremlinVersion;

            if (file_exists($this->config->tinkergraph_port.'/db/tinkergraph.pid')) {
                $stats['running'] = 'Yes (PID : '.trim(file_get_contents($this->config->tinkergraph_port.'/db/tinkergraph.pid')).')';
            }
        }
        
        return $stats;
    }
    
    private function getTinkerGraphNeo4j() {
        $stats = array();

        if (empty($this->config->gsneo4j_folder)) {
            $stats['configured'] = 'No tinkergraph/neo4j configured in config/exakat.ini.';
        } elseif (!file_exists($this->config->gsneo4j_folder)) {
            $stats['installed'] = 'No (folder : '.$this->config->gsneo4j_folder.')';
        } elseif (!file_exists($this->config->gsneo4j_folder.'/ext/neo4j-gremlin/')) {
            $stats['installed'] = 'Partially (missing neo4j folder : '.$this->config->gsneo4j_folder.')';
        } else {
            $stats['installed'] = 'Yes (folder : '.$this->config->gsneo4j_folder.')';
            $stats['host'] = $this->config->gsneo4j_host;
            $stats['port'] = $this->config->gsneo4j_port;

            $plugins = glob("{$this->config->gsneo4j_folder}/ext/neo4j-gremlin/plugin/*.jar");
            if (count($plugins) !== 73) {
                $stats['grapes failed'] = 'Partially installed neo4j plugin. Please, check installation docs, and "grab" again : some of the files are missing for neo4j.';
            }
            
            $gremlinJar = glob($this->config->gsneo4j_folder.'/lib/gremlin-core-*.jar');
            $gremlinVersion = basename(array_pop($gremlinJar));
            //gremlin-core-3.2.5.jar
            $gremlinVersion = substr($gremlinVersion, 13, -4);
            $stats['gremlin version'] = $gremlinVersion;

            $neo4jJar = glob($this->config->gsneo4j_folder.'/ext/neo4j-gremlin/lib/neo4j-*.jar');
            $neo4jJar = array_filter($neo4jJar, function($x) { return preg_match('#/neo4j-\d\.\d\.\d\.jar#', $x); });
            $neo4jVersion = basename(array_pop($neo4jJar));

            //neo4j-2.3.3.jar
            $neo4jVersion = substr($neo4jVersion, 6, -4);
            $stats['neo4j version'] = $neo4jVersion;
            
            if (file_exists($this->config->gsneo4j_folder.'/db/gsneo4j.pid')) {
                $stats['running'] = 'Yes (PID : '.trim(file_get_contents($this->config->gsneo4j_folder.'/db/gsneo4j.pid')).')';
            }
        }

        return $stats;
    }
}
?>
