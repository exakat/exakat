<?php

$expected     = array('if($this->config->thema !== null) { /**/ } elseif($b->nope->program !== null) { /**/ } else { /**/ } ',
                     );

$expected_not = array('if($this->config->thema !== null) { /**/ } elseif($this->nope->program !== null) { /**/ } else { /**/ } ',
                     );

?>