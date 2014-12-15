<?php

namespace Report\Format\Markdown;

class Horizontal extends \Report\Format\Markdown { 
    public function render($output, $data) {
        if (count($data) == 0) { return "\nNo results to display\n"; }
        $markdown = '';
        foreach($data as $row) {
$markdown .= <<<MARKDOWN
| code |{$row['code']}|
| line |{$row['line']}|
| file |{$row['file']}|
| description |{$row['desc']}|
|||

MARKDOWN;
            }

        $markdown = <<<MARKDOWN
| A |B |
| -------:        | -------:          |
$markdown

MARKDOWN;

        $output->push("\n".trim($markdown)."\n");
    }

}

?>
