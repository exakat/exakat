<?php

const PIPEFILE = '/tmp/api';

$initTime = microtime(true);

$commands = explode('/', parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH));
unset($commands[0]);
$command = array_shift($commands);

$orders = array('stop', 'init', 'status', 'report', 'stats', 'dashboard');

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
    if (isset($_REQUEST['vcs'])) {
        $url = parse_url($_REQUEST['vcs']);
        if (!isset($url['scheme'], $url['host'], $url['path'])) {
            error('Malformed VCS', '');
        }
        
        $vcs = unparse_url($url);

        $project = autoprojectname();

        shell_exec('__PHP__ __EXAKAT__ init -p '.$project.' -R '.$vcs);
        shell_exec('__PHP__ __EXAKAT__ queue -p '.$project);
        serverLog("init : $project $vcs ".date('r'));

        echo json_encode(array('project' => $project, 
                               'start' => date('r')));
    } else {
        error('Missing VCS/code', '');
    }
}

function status($args) {
    $uuid = checksUUID($args[0]);
    
    if ($uuid === false) {
        error('Unknown UUID');
    }
    
    if (!file_exists(__DIR__.'/'.$args[0])) {
	    error('No such UUID');
    } elseif (file_exists(__DIR__.'/'.$args[0].'/report.zip')) {
        $status = array(
            'Status'       => 'finished'
        );
        echo json_encode($status);
    } else {
        $status = array(
            'Status'       => 'queued'
        );
        echo json_encode($status);
    }
}

function report($args) {
    $uuid = checksUUID($args[0]);
    
    if ($uuid === false) {
        error('Unknown UUID');
    }
    
    $reportPath = __DIR__.'/'.$uuid.'/report.zip';
    if (!file_exists($reportPath)) {
	    error('No such UUID');
    } else {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"report.zip\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: ".filesize($reportPath));
        ob_end_flush();
        readfile($reportPath);
        die();
    }
}

function stats($args) {
    $uuid = checksUUID($args[0]);
    
    if ($uuid === false) {
        error('Unknown UUID');
    }
    
    $reportPath = __DIR__.'/'.$uuid.'/stats.json';
    if (!file_exists($reportPath)) {
	    error('No such UUID');
    } else {
        readfile($reportPath);
    }
}

function dashboard($args) {
    $files = glob(__DIR__.'/*');
    finish(array('projects' => $files,
          ));
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

function checksUUID($value) {
    if (strlen($value) != 10) {
        return false;
    }
    if (preg_match('/[^a-z0-9]/', $value)) {
        return false;
    }
    
    return $value;
}

function finish($message) {
    echo json_encode($message);
    die();
}

function error($message, $project) {
    finish(array('error'   => $message,
                 'project' => $project,
                )
          );
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
