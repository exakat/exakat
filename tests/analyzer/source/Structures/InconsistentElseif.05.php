<?php

if(file_exists("{$a->config->gsneo4j_folder}/db/gremlin.pid")) { /**/ } 
elseif(file_exists("{$a->config->gsneo4j_folder}/db/gsneo4j.pid")) { /**/ } 
else { /**/ }

if(file_exists("{$b->config->gsneo4j_folder}/db/gremlin.pid")) { /**/ } 
elseif(file_exists("{$b->nope->gsneo4j_folder}/db/gsneo4j.pid")) { /**/ } 
else { /**/ }

if(file_exists("{$c->config->gsneo4j_folder}/db/gremlin.pid")) { /**/ } 
elseif(file_exists("{$d->nope->gsneo4j_folder}/db/gsneo4j.pid")) { /**/ } 
else { /**/ }

?>