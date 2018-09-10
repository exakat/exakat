<?php

const PIPEFILE = '/tmp/queue.exakat';

$initTime = microtime(true);

$commands = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
unset($commands[0]);
$command = array_shift($commands);

$orders = array('stop', 

                'init', 
                'project', 
                'update', 
                'report', 
                'fetch', 

                'onepage', 
                'status', 
                'list', 
                'stop', 
                'config', 
                'queue',
                );

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
        
        $pass = '';
        if (!empty($url['user'])) {
            $pass .= escapeshellarg($url['user']).':'.(isset($url['pass']) ? escapeshellarg($url['pass']) : '').'@';
        }
        $vcs = $url['scheme'].'://'.
               $pass.
               $url['host'].
               (!empty($url['port']) ? ':'.$url['port'] : '').
               $url['path'];
        
        if (empty($project)) {
            $project = autoprojectname();
        }

        print '__PHP__ __EXAKAT__ init -p '.$project.' -R '.$vcs;
        shell_exec('__PHP__ __EXAKAT__ init -p '.$project.' -R '.$vcs);
    } elseif (isset($_REQUEST['code'])) {
        $php = $_REQUEST['code'];
        if (strpos($php, '<?php') === false) {
            error('Invalide code', '');
        }

        if (empty($project)) {
            $project = autoOnagepageName();
        }
        
        file_put_contents(__DIR__.'/onepage/code/'.$project.'.php', $php);
        shell_exec('__PHP__ __EXAKAT__ queue -f '.$project);
    } else {
        error('Missing Onepage/code', '');
    }

    echo json_encode(compact('project'));
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

    shell_exec('__PHP__ __EXAKAT__ update -p '.$project);
    echo json_encode(compact('project'));
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
    
    echo shell_exec('__PHP__ __EXAKAT__ queue -p '.$project);
    echo json_encode(compact('project'));
}

function queue($args) {
    if (!isset($_REQUEST['json'])) {
        return;
    }
    
    $json = $_REQUEST['json'];
    $jsonArray = json_decode($json);
    if ($jsonArray === null) {
        echo 'Not Json valid';
    }
    if (!is_array($jsonArray)) {
        echo 'Not Json array';
    }
    
    array_shift($jsonArray);
    
    echo shell_exec('__PHP__ __EXAKAT__ '.implode(' ', $jsonArray));
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
    
    readfile(__DIR__."/$project/dump.sqlite");
}

function status($args) {
    global $initTime;
    
    if (isset($args[0]) && !empty($args[0])) {
        if (!file_exists(__DIR__.'/'.$args[0])) {
    	    error('No such project', $args[0]);
        } elseif (file_exists(__DIR__.'/'.$args[0].'/')) {
            $json = shell_exec('__PHP__ __EXAKAT__ status -p '.$args[0].' -json');
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
    if (empty($args[0])) {
        return;
    }

    $project = $args[0];
    
    if (!file_exists(__DIR__."/$project/config.ini")) {
        return;
    }
    
    $status = array();
    
    $ini = file_get_contents(__DIR__."/$project/config.ini");
    $php_versions = array('7.3', '7.2', '7.1', '7.0', '5.6', '5.5', '5.4', '5.3');
    if (!empty($_REQUEST['phpversion']) &&
        in_array($_REQUEST['phpversion'], $php_versions)) {
        $ini = preg_replace("/phpversion = .+?\n/", 'phpversion = '.$_REQUEST['phpversion'].PHP_EOL, $ini);
        $status[] = 'phpversion';
    }

    if (!empty($_REQUEST['file_extensions'])) {
        $extensions = explode(',', $_REQUEST['file_extensions']);
        $extensions = array_filter($extensions, function ($x) { return preg_match('/^\.[a-zA-Z0-9]+$/', $x); });

        if (!empty($extensions)) {
            $extensions = implode(',', $extensions);
            $ini = preg_replace("/file_extensions = .+?\n/", 'file_extensions = "'.$extensions.'";'.PHP_EOL, $ini);
        }
        $status[] = 'file_extensions';
    }

    if (!empty($_REQUEST['ignore_dirs']) && 
        is_array($_REQUEST['ignore_dirs'])) {

        $ignore_dirs = $_REQUEST['ignore_dirs'];
        $ignore_dirs = array_map(function($x) { return '"'.str_replace('"', '\\"', substr($x, 0, 250)).'"'; }, $ignore_dirs);
        $ini = preg_replace("/(ignore_dirs\[\] = .+?\n)+/s", 
                            'ignore_dirs[] = '.implode(PHP_EOL.'ignore_dirs[] = ', $ignore_dirs ).PHP_EOL, 
                            $ini);
        $status[] = 'ignore_dirs';
    }

    $regexBranchTag = '/^[a-zA-Z0-9_\.-]+$/';
    if (!empty($_REQUEST['branch']) && 
        preg_match($regexBranchTag, $_REQUEST['branch'])) {

        $ini = preg_replace("/project_branch\s*=\s*\"[^\"]*?\";\n/s", 
                            'project_branch      = "'.$_REQUEST['branch'].'";'.PHP_EOL, 
                            $ini);
        $ini = preg_replace("/project_tag\s*=\s*\"\w*\";\n/s", 
                            'project_tag         = "";'.PHP_EOL, 
                            $ini);
        $status[] = 'branch';
    } elseif (!empty($_REQUEST['tag']) && 
        preg_match($regexBranchTag, $_REQUEST['tag'])) {

        $ini = preg_replace("/project_branch\s*=\s*\"[^\"]*?\";\n/s", 
                            'project_branch      = "";'.PHP_EOL, 
                            $ini);
        $ini = preg_replace("/project_tag\s*=\s*\"\w*\";\n/s", 
                            'project_tag         = "'.$_REQUEST['tag'].'";'.PHP_EOL, 
                            $ini);
        $status[] = 'tag';
    }
    
    if (!empty($_REQUEST['name']) && 
        preg_match($regexBranchTag, $_REQUEST['name'])) {

        $ini = preg_replace("/project_name\s*=\s*\"[^\"]*?\";\n/s", 
                            'project_name        = "'.$_REQUEST['name'].'";'.PHP_EOL, 
                            $ini);
        $status[] = 'name';
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
        die(json_encode($status));
    }
    $size = file_put_contents(__DIR__."/$project/config.ini", $ini);
    
    $status = array('saved'   => $size, 
                    'options' => $status);
    die(json_encode($status));
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
    if ($fp === false) {
        echo json_encode(array('status' => 'Could not push to queue'));
        return;
    }

    fwrite($fp, "$id\n");
    fclose($fp);
}

function autoProjectName() {
    $letters = range('a', 'z');
    try {
        $return = $letters[random_int(0, 25)].random_int(0, 1000000000);
    } catch(Throwable $e) {
        $return = 'a';
    }
    
    return $return;
}

function autoOnagepageName() {
    $letters = range('A', 'Z');
    try {
        $return = $letters[random_int(0, 25)].random_int(0, 1000000000);
    } catch(Throwable $e) {
        $return = 'a';
    }
    
    return $return;
}

function error($error, $project) {
    die(json_encode(compact('error', 'project')));
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
