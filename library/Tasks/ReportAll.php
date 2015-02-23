<?php

namespace Tasks;

use Everyman\Neo4j\Client,
	Everyman\Neo4j\Gremlin\Query;

class ReportAll implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $formats = array('Markdown', 'Sqlite', 'Ace', 'Html', 'Text', /* 'pdf', 'odt' */);
        $reportType = 'Premier';
        
        foreach($formats as $format) {
            print "Reporting $format\n";
            $args = array ( 1 => 'report',
                            2 => '-p',
                            3 => $config->project,
                            4 => '-f',
                            5 => 'report',
                            6 => '-format',
                            7 => $format,
                            8 => '-report',
                            9 => $reportType,
                            );
            $config = \Config::factorySingle($args);
            
            $report = new Report();
            $report->run($config);
            unset($report);
        }
    }
}

?>