<?php 

        if($type && is_array($type))
            $sql.=' AND thread.thread_type IN('.implode(',', db_input($type)).')';
        elseif($type)
            $sql.=' AND thread.thread_type='.db_input($type);
?>