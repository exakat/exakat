<?php

namespace Tasks;

use Everyman\Neo4j\Client,
	Everyman\Neo4j\Gremlin\Query;

class Stat implements Tasks {
    private $client = null;
    
    public function run(\Config $config) {
        $project = $config->project;

        $client = new Client();
        $stats = new \Stats($client);
        if ($config->filename) {
            $stats->setFileFilter($config->filename);
        }
        $stats->collect();
        $stats = $stats->toArray();

        if ($config->json) {
            $output = json_encode($stats);
        } elseif ($config->table) {
            $output = $this->table_encode($stats);
        } else {
            $output = $this->text_encode($stats);
        }

        if ($config->output) {
            $fp = fopen($config->filename, 'w+');
            fwrite($fp, $output);
            fclose($fp);
        } else {
            print $output;
        }
    }

    private function table_encode($stats) {
        $html = "<html><body>";

        foreach($stats as $name => $value) {
            $html .= "<tr><td>$name</td><td>$value</td></tr>\n";
        }

        $html .= "</body></html>";
        return $html;
    }

    private function text_encode($stats) {
        $html = "Statistics for the whole server\n\n";

        foreach($stats as $name => $value) {
            $html .= "$name : $value\n";
        }

        $html .= "\n";
        return $html;
    }
}

?>