<?php

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');
JLoader::import('KBIntegrator', JPATH_PLUGINS . DS . 'kbi');

JoomlaFunction(); // Not a Joomla function

class MyController extends JController {
	function display($message) {
        echo $message;
    }
}

?>