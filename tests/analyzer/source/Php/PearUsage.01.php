<?php

class y {
    function raiseError($error)
    {
        $this->graph = new Image_GraphViz(
            true,
            array(
                'fontname'  => 'Verdana',
                'fontsize'  => 12.0,
                //'fontcolor' => 'gray5',
                'rankdir' => 'LR', // left-to-right
            )
        );
        if (PEAR::isError($error)) {
            $error = $error->getMessage();
        }
        trigger_error($error, E_USER_WARNING);
        return Console_Getopt::getopt2($argv, $short_options);
    }

}
?>