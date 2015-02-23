<?php

namespace Tasks;

use Everyman\Neo4j\Client,
	Everyman\Neo4j\Gremlin\Query;

class Report implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $client = new Client();
        $db = new \Db();

        $datastore = new \Datastore($config);
        \Analyzer\Analyzer::$datastore = $datastore;

        if (!class_exists("\\Report\\Format\\".$config->format)) {
            print "Format '{$config->format}' doesn't exist.\nAborting\n";
            
            // @todo suggest some reports? Use a default one. 
            die();
        }

        print "Building report ".$config->report." for project ".$config->project." in file ".$config->file.", with format ".$config->format."\n";
        $begin = microtime(true);

        $reportClass = "\\Report\\Report\\".$config->report;
        $report = new $reportClass($config->project, $client, $db);
        $report->prepare();
        echo $config->format, ' ', $config->filename;
        $size = $report->render($config->format, $config->filename);

        $end = microtime(true);
        print "Processing time : ".number_format($end - $begin, 2)." s\n";
        print "Done\n";
    }
}

?>