<?php

namespace Report\Format\Markdown;

class Section extends \Report\Format\Markdown { 
    public function render($output, $data) {
        // todo link to the actual section  ?
        $output->push("\n".str_repeat("#", $data->getLevel())." <a name=\"".$data->getId()."\"></a>".$data->getName()." \n");
    }

}

?>