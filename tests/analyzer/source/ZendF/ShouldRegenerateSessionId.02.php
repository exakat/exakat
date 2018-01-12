<?php
   $session = $e->getApplication()
                ->getServiceManager()
                ->get('Zend\Session\SessionManager');
                
   $session->regenerateId();
?>