<?php

namespace Report\Format\Markdown;

class Summary extends \Report\Format\Markdown { 
    public function render($output, $data) {
        $markdown = '';
        foreach($data as $row) {
            if (get_class($row) != "Report\\Template\\Section") { continue; }
$markdown .= str_repeat("  ", $row->getLevel())."+ [".$row->getName()."](#".$row->getId().")\n";
            }

        $markdown = <<<MARKDOWN

$markdown

MARKDOWN;

        $output->push("$markdown\n");
    }

}

?>
