<?php

bzclose(bzopen('file'));

sem_release(sem_get('semaphore'));

// custom code
zzclose(zzopen('resource'));
?>