<?php

$expected     = array('bzclose(bzopen(\'file\'))',
                      'sem_release(sem_get(\'semaphore\'))',
                     );

$expected_not = array('zzclose(zzopen(\'resource\'))',
                     );

?>