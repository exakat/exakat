<?php

const PIPEFILE = '/tmp/onepageQueue';

$initTime = microtime(true);

$commands = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
unset($commands[0]);
$command = array_shift($commands);

$orders = array('stop', 'init', 'update', 'project', 'onepage', 'report', 'status', 'list', 'stop', 'config');

if (!in_array($command, $orders)) {
    serverLog("unknown command : $command");
    die( 'Exakat server (unknown command)');
}

$command($commands);

$endTime = microtime(true);
serverLog(substr($command."\t".floor(1000*($endTime - $initTime))."\t".implode("\t", $commands), 0, 256));
//End script

/// Function definitions

function stop($args) {
    serverLog("Shutting down\n");
    $pid = getmypid();
    echo "<p>Shutting down server (pid : $pid)</p>";
    ob_flush();

    unlink(__FILE__);

    exec('kill '.getmypid());
    // This is killed.
}

function init($args) {
    if (isset($_REQUEST['project'])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $_REQUEST['project']);
        if (empty($project)) {
            $project = '';
        } elseif (file_exists(__DIR__.'/'.$project)) {
            error('Project already exists', $project);
        }
    } else {
        $project = '';
    }
    
    if (isset($_REQUEST['vcs'])) {
        $url = parse_url($_REQUEST['vcs']);
        if (!isset($url['scheme'], $url['host'], $url['path'])) {
            error('Malformed VCS', '');
        }
        
        $vcs = $url['scheme'].'://'.$url['host'].(!empty($url['port']) ? ':'.$url['port'] : '').$url['path'];
        
        if (empty($project)) {
            $project = autoprojectname();
        }

        shell_exec('/usr/bin/php exakat.phar init -p '.$project.' -R '.escapeshellarg($vcs));
    } elseif (isset($_REQUEST['code'])) {
        $php = $_REQUEST['code'];
        if (strpos($php, '<?php') === false) {
            error('Invalide code', '');
        }

        if (empty($project)) {
            $project = autoOnagepageName();
        }
        
        file_put_contents(__DIR__.'/onepage/code/'.$project.'.php', $php);
        shell_exec('/usr/bin/php exakat.phar queue -f '.$project);
    } else {
        error('Missing VCS/code', '');
    }

    echo json_encode(array('project' => $project));
}

function update($args) {
    if (isset($_REQUEST['project'])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $_REQUEST['project']);
        if (empty($project)) {
            error('Missing project', '');
        }

        if (!file_exists(__DIR__.'/'.$project)) {
            error('No such project', $project);
        }
    } else {
        error('No such project', '');
    }

    shell_exec('/usr/bin/php exakat.phar update -p '.$project);
    echo json_encode(array('project' => $project));
}

function project($args) {
    if (isset($args[0])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $args[0]);
        if (empty($project)) {
            error('Missing project', '');
        }

        if (!file_exists(__DIR__.'/'.$project)) {
            error('No such project', '');
        }
    } else {
        error('No such project', '');
    }
    
    echo shell_exec('/usr/bin/php exakat.phar queue -p '.$project);
    echo json_encode(array('project' => $project));
}

function onepage($args) {
    if (isset($args[0])) {
        $file = preg_replace('/[^a-zA-Z0-9-_]/', '', $args[0]);
        if (empty($file)) {
            error('Missing file', '');
        }

        if (!file_exists(__DIR__.'/onepage/code/'.$file.'.php')) {
            error('No such file', '');
        }
    } else {
        error('No such file', '');
    }

    if (!file_exists(__DIR__.'/onepage/reports/'.$file.'.json')) {
        error('No such results', '');
    }
    
    readfile(__DIR__.'/onepage/reports/'.$file.'.json');
}

function report($args) {
    if (isset($args[0])) {
        $project = preg_replace('/[^a-zA-Z0-9-_]/', '', $args[0]);
        if (empty($project)) {
            error('Missing project', '');
        }

        if (!file_exists(__DIR__.'/'.$project)) {
            error('No such project', $project);
        }
    } else {
        error('No such project', '');
    }
    
    // Check on report, then get dump.sqlite.
    if (!file_exists(__DIR__.'/'.$project.'/report')) {
        error('No report available', $project);
    }
    
    readfile(__DIR__.'/'.$project.'/dump.sqlite');
}

function status($args) {
    global $initTime;
    
    if (isset($args[0]) && !empty($args[0])) {
        if (!file_exists(__DIR__.'/'.$args[0])) {
    	    error('No such project', $args[0]);
	    } elseif (!file_exists(__DIR__.'/'.$args[0].'/code/')) {
            error('No code found', $args[0]);
        } elseif (file_exists(__DIR__.'/'.$args[0].'/')) {
            $json = shell_exec('/usr/bin/php exakat.phar status -p '.$args[0].' -json');
            echo $json;
        } else {
            error('No such project', $args[0]);
        }
    } else {
        $status = array(
            'Status'       => 'OK',
            'Running Time' => duration(microtime(true) - $initTime),
            'Init Time '   => date('r', (int) $initTime),
            'Queue'        => file_exists(PIPEFILE) ? 'Yes' : 'No'
        );
        echo json_encode($status);
    }
}

function config($args) {
    $project = $args[0];
    
    if (empty($project)) {
        return;
    }
    
    if (!file_exists(__DIR__.'/'.$project.'/config.ini')) {
        return;
    }
    
    $status = array();
    
    $ini = file_get_contents(__DIR__.'/'.$project.'/config.ini');
    $php_versions = array('7.2', '7.1', '7.0', '5.6', '5.5', '5.4', '5.3');
    if (!empty($_REQUEST['phpversion']) &&
        in_array($_REQUEST['phpversion'], $php_versions)) {
        $ini = preg_replace("/phpversion = .+?\n/", 'phpversion = '.$_REQUEST['phpversion'], $ini);
        $status[] = 'phpversion';
    }

    if (!empty($_REQUEST['file_extensions'])) {
        $extensions = explode(',', $_REQUEST['file_extensions']);
        $extensions = array_filter($extensions, function ($x) { return preg_match('/^\.[a-zA-Z0-9]+$/', $x); });

        if (!empty($extensions)) {
            $extensions = join(',', $extensions);
            $ini = preg_replace("/file_extensions = .+?\n/", 'file_extensions = '.$extensions, $ini);
        }
        $status[] = 'file_extensions';
    }

    if (!empty($_REQUEST['ignore_dirs']) && 
        is_array($_REQUEST['ignore_dirs'])) {

        $ini = preg_replace("/(ignore_dirs\[\] = .+?\n)+/s", 
                            'ignore_dirs[] = '.implode(PHP_EOL.'ignore_dirs[] = ', $_REQUEST['ignore_dirs']).PHP_EOL, 
                            $ini);
        $status[] = 'ignore_dirs';
    }

    if (!empty($_REQUEST['include_dirs']) && 
        is_array($_REQUEST['include_dirs'])) {

        $ini = preg_replace("/(include_dirs\[\] = .+?\n)+/s", 
                            'include_dirs[] = '.implode(PHP_EOL.'include_dirs[] = ', $_REQUEST['include_dirs']).PHP_EOL, 
                            $ini);
        $status[] = 'include_dirs';
    }

    if (!empty($_REQUEST['userpass']) && 
        preg_match('/^[^:\s]*:[^:\s]*$/', $_REQUEST['userpass'])) {
        if (preg_match('/project_url\s*=\s*"(.*?)";\s/s', $ini, $r)) {
            $url = $r[1];
            list($user, $pass) = explode(':', $_REQUEST['userpass']);
            
            $details = parse_url($url);
            if (empty($user) && empty($pass)) {
                unset($details['user'], $details['pass']);
            } else {
                $details['user'] = escapeshellarg($user);
                $details['pass'] = escapeshellarg($pass);
            }
            $url = unparse_url($details);

            $ini = preg_replace('/project_url\s*=\s*"(.*?)";\s/s', 
                                'project_url         = "'.$url.'";'.PHP_EOL, 
                                $ini);
        }
        $status[] = 'userpass';
    }

    if (empty($status)) {
        echo json_encode($status);
        die();
    }
    $size = file_put_contents(__DIR__.'/'.$project.'/config.ini', $ini);
    
    $status = array('saved' => $size, 
                    'options' => $status);
    echo json_encode($status);
    die();
}


// Helper functions
function duration($duration) {
    $duration = (int) $duration;
    
    return $duration;
}

function pushToQueue($id) {
    if (!file_exists(PIPEFILE)) {
        echo json_encode(array('status' => 'Server not ready'));
        return;
    }
    
    $fp = fopen(PIPEFILE, 'a');
    if ($fp !== false) {
        fwrite($fp, "$id\n");
        fclose($fp);
    } else {
        echo json_encode(array('status' => 'Could not push to queue'));
        return;
    }
}

function autoProjectName() {
    $files = glob(__DIR__.'/*');
    return 'a'.count($files);
}

function autoOnagepageName() {
    $files = glob(__DIR__.'/onepage/code/*');
    return 'o'.count($files);
}

function error($message, $project) {
    die(json_encode(array('error' => $message,
                          'project' => $project)));
}

function serverLog($message) {
    $fp = fopen(__DIR__.'/server.log', 'a');
    if ($fp !== false) {
        fwrite($fp, date('r')."\t$message\n");
        fclose($fp);
    }
}

function unparse_url($parsed_url) {
    $scheme   = isset($parsed_url['scheme'])   ? $parsed_url['scheme'].'://' : '';
    $host     = isset($parsed_url['host'])     ? $parsed_url['host']           : '';
    $port     = isset($parsed_url['port'])     ? ':'.$parsed_url['port']     : '';
    $user     = isset($parsed_url['user'])     ? $parsed_url['user']           : '';
    $pass     = isset($parsed_url['pass'])     ? ':'.$parsed_url['pass']     : '';
    $pass     = ($user || $pass)               ? $pass.'@'                      : '';
    $path     = isset($parsed_url['path'])     ? $parsed_url['path']           : '';
    $query    = isset($parsed_url['query'])    ? '?'.$parsed_url['query']    : '';
    $fragment = isset($parsed_url['fragment']) ? '#'.$parsed_url['fragment'] : '';
    return $scheme.$user.$pass.$host.$port.$path.$query.$fragment;
}

?>
