<?php
if ($obj instanceof ZendServer_JobQueue_Job_Abstract) {
    try {
        $obj->run();
        ZendJobQueue::setCurrentJobStatus(ZendJobQueue::OK);
        exit;
    } catch (Exception $e) {
        zend_monitor_set_aggregation_hint(get_class($obj) . ': ' . $e->getMessage());
        zend_monitor_custom_event('Failed Job', $e->getMessage());
    }
}
?>