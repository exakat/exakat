.. _Cases:

Real Code Cases
---------------

Introduction
############

All the examples in this section are real code, extracted from major PHP applications. 


Examples
########

Adding Zero
===========

.. _thelia-structures-addzero:

Thelia
^^^^^^

:ref:`adding-zero`, in core/lib/Thelia/Model/Map/ProfileResourceTableMap.php:250. 

This return statement is doing quite a lot, including a buried '0 + $offset'. This call is probably an echo to '1 + $offset', which is a little later in the expression.

.. code-block:: php

    return serialize(array((string) $row[TableMap::TYPE_NUM == $indexType ? 0 + $offset : static::translateFieldName('ProfileId', TableMap::TYPE_PHPNAME, $indexType)], (string) $row[TableMap::TYPE_NUM == $indexType ? 1 + $offset : static::translateFieldName('ResourceId', TableMap::TYPE_PHPNAME, $indexType)]));


--------


.. _openemr-structures-addzero:

OpenEMR
^^^^^^^

:ref:`adding-zero`, in interface/forms/fee_sheet/new.php:466:534. 

$main_provid is filtered as an integer. $main_supid is then filtered twice : one with the sufficent (int) and then, added with 0.

.. code-block:: php

    if (!$alertmsg && ($_POST['bn_save'] || $_POST['bn_save_close'] || $_POST['bn_save_stay'])) {
        $main_provid = 0 + $_POST['ProviderID'];
        $main_supid  = 0 + (int)$_POST['SupervisorID'];
        //.....

error_reporting() With Integers
===============================

.. _sugarcrm-structures-errorreportingwithinteger:

SugarCrm
^^^^^^^^

:ref:`error\_reporting()-with-integers`, in modules/UpgradeWizard/silentUpgrade_step1.php:436. 

This only displays E_ERROR, the highest level of error reporting. It should be checked, as it happens in the 'silentUpgrade' script. 

.. code-block:: php

    ini_set('error_reporting', 1);

Not Not
=======

.. _cleverstyle-structures-notnot:

Cleverstyle
^^^^^^^^^^^

:ref:`not-not`, in modules/OAuth2/OAuth2.php:190. 

This double-call returns $results as a boolean, preventing a spill of data to the calling method. (bool) would be clearer here.

.. code-block:: php

    $result = $this->db_prime()->q(
    			[
    				DELETE FROM `[prefix]oauth2_clients`
    				WHERE `id` = '%s',
    				DELETE FROM `[prefix]oauth2_clients_grant_access`
    				WHERE `id`	= '%s',
    				DELETE FROM `[prefix]oauth2_clients_sessions`
    				WHERE `id`	= '%s'
    			],
    			$id
    		);
    		unset($this->cache->{'/'});
    		return !!$result;


--------


.. _tine20-structures-notnot:

Tine20
^^^^^^

:ref:`not-not`, in /tine20/Calendar/Controller/MSEventFacade.php:392. 

It seems that !! is almost superfluous, as a property called 'is_deleted' should already be a boolean.

.. code-block:: php

    foreach ($exceptions as $exception) {
                    $exception->assertAttendee($this->getCalendarUser());
                    $this->_prepareException($savedEvent, $exception);
                    $this->_preserveMetaData($savedEvent, $exception, true);
                    $this->_eventController->createRecurException($exception, !!$exception->is_deleted);
                }

Strpos()-like Comparison
========================

.. _piwigo-structures-strposcompare:

Piwigo
^^^^^^

:ref:`strpos()-like-comparison`, in admin/include/functions.php:2585. 

preg_match may return 0 if not found, and null if the $pattern is erroneous. While hardcoded regex may be checked at compile time, dynamically built regex may fail at execution time. This is particularly important here, since the function may be called with incoming data for maintenance : 'clear_derivative_cache($_GET['type']);' is in the /admin/maintenance.php.

.. code-block:: php

    function clear_derivative_cache_rec($path, $pattern)
    {
      $rmdir = true;
      $rm_index = false;
    
      if ($contents = opendir($path))
      {
        while (($node = readdir($contents)) !== false)
        {
          if ($node == '.' or $node == '..')
            continue;
          if (is_dir($path.'/'.$node))
          {
            $rmdir &= clear_derivative_cache_rec($path.'/'.$node, $pattern);
          }
          else
          {
            if (preg_match($pattern, $node))


--------


.. _thelia-structures-strposcompare:

Thelia
^^^^^^

:ref:`strpos()-like-comparison`, in core/lib/Thelia/Controller/Admin/FileController.php:198. 

preg_match is used here to identify files with a forbidden extension. The actual list of extension is provided to the method via the parameter $extBlackList, which is an array. In case of mis-configuration by the user of this array, preg_match may fail : for example, when regex special characters are provided. At that point, the whole filter becomes invalid, and can't distinguish good files (returning false) and other files (returning NULL). It is safe to use === false in this situation.

.. code-block:: php

    if (!empty($extBlackList)) {
                $regex = "#^(.+)\.(".implode("|", $extBlackList).")$#i";
    
                if (preg_match($regex, $realFileName)) {
                    $message = $this->getTranslator()
                        ->trans(
                            'Files with the following extension are not allowed: %extension, please do an archive of the file if you want to upload it',
                            [
                                '%extension' => $fileBeingUploaded->getClientOriginalExtension(),
                            ]
                        );
                }
            }

Used Once Variables
===================

.. _shopware-variables-variableusedonce:

shopware
^^^^^^^^

:ref:`used-once-variables`, in _sql/migrations/438-add-email-template-header-footer-fields.php:115. 

In the updateEmailTemplate method, $generatedQueries collects all the generated SQL queries. $generatedQueries is not initialized, and never used after initialization. 

.. code-block:: php

    private function updateEmailTemplate($name, $content, $contentHtml = null)
        {
            $sql = <<<SQL
    UPDATE `s_core_config_mails` SET `content` = "$content" WHERE `name` = "$name" AND dirty = 0
    SQL;
            $this->addSql($sql);
    
            if ($contentHtml != null) {
                $sql = <<<SQL
    UPDATE `s_core_config_mails` SET `content` = "$content", `contentHTML` = "$contentHtml" WHERE `name` = "$name" AND dirty = 0
    SQL;
                $generatedQueries[] = $sql;
            }
    
            $this->addSql($sql);
        }


--------


.. _vanilla-variables-variableusedonce:

Vanilla
^^^^^^^

:ref:`used-once-variables`, in library/core/class.configuration.php:1461. 

In this code, $cachedConfigData is collected after storing date in the cache. Gdn::cache()->store() does actual work, so its calling is necessary. The result, collected after execution, is not reused in the rest of the method (long method, not all is shown here). Removing such variable is a needed clean up after development and debug, but also prevents pollution of the variable namespace.

.. code-block:: php

    // Save to cache if we're into that sort of thing
                    $fileKey = sprintf(Gdn_Configuration::CONFIG_FILE_CACHE_KEY, $this->Source);
                    if ($this->Configuration && $this->Configuration->caching() && Gdn::cache()->type() == Gdn_Cache::CACHE_TYPE_MEMORY && Gdn::cache()->activeEnabled()) {
                        $cachedConfigData = Gdn::cache()->store($fileKey, $data, [
                            Gdn_Cache::FEATURE_NOPREFIX => true,
                            Gdn_Cache::FEATURE_EXPIRY => 3600
                        ]);
                    }

Empty Classes
=============

.. _wordpress-classes-emptyclass:

WordPress
^^^^^^^^^

:ref:`empty-classes`, in wp-includes/SimplePie/Core.php:54. 

Empty class, but documented as backward compatibility. 

.. code-block:: php

    /**
     * SimplePie class.
     *
     * Class for backward compatibility.
     *
     * @deprecated Use {@see SimplePie} directly
     * @package SimplePie
     * @subpackage API
     */
    class SimplePie_Core extends SimplePie
    {
    
    }

Non Ascii Variables
===================

.. _magento-variables-variablenonascii:

Magento
^^^^^^^

:ref:`non-ascii-variables`, in dev/tests/functional/tests/app/Mage/Checkout/Test/Constraint/AssertOrderWithMultishippingSuccessPlacedMessage.php:52. 

The initial C is actually a russian C.

.. code-block:: php

    $сheckoutMultishippingSuccess

Multiple Index Definition
=========================

.. _magento-arrays-multipleidenticalkeys:

Magento
^^^^^^^

:ref:`multiple-index-definition`, in app/code/core/Mage/Adminhtml/Block/System/Convert/Gui/Grid.php:80. 

'type' is defined twice. The first one, 'options' is overwritten.

.. code-block:: php

    $this->addColumn('store_id', array(
                'header'    => Mage::helper('adminhtml')->__('Store'),
                'type'      => 'options',
                'align'     => 'center',
                'index'     => 'store_id',
                'type'      => 'store',
                'width'     => '200px',
            ));


--------


.. _mediawiki-arrays-multipleidenticalkeys:

MediaWiki
^^^^^^^^^

:ref:`multiple-index-definition`, in resources/Resources.php:223. 

'target' is repeated, though with the same values. This is just dead code.

.. code-block:: php

    // inside a big array
    	'jquery.getAttrs' => [
    		'targets' => [ 'desktop', 'mobile' ],
    		'scripts' => 'resources/src/jquery/jquery.getAttrs.js',
    		'targets' => [ 'desktop', 'mobile' ],
    	],
        // big array continues

Incompilable Files
==================

.. _xataface-php-incompilable:

xataface
^^^^^^^^

:ref:`incompilable-files`, in lib/XML/Tree.php:289. 

Compilation error with PHP 7.2 version.

.. code-block:: php

    syntax error, unexpected 'new' (T_NEW)

Multiple Constant Definition
============================

.. _dolibarr-constants-multipleconstantdefinition:

Dolibarr
^^^^^^^^

:ref:`multiple-constant-definition`, in htdocs/main.inc.php:914. 

All is documented here : 'Constants used to defined number of lines in textarea'. Constants are not changing during an execution, and this allows the script to set values early in the process, and have them used later, in the templates. Yet, building constants dynamically may lead to confusion, when developpers are not aware of the change. 

.. code-block:: php

    // Constants used to defined number of lines in textarea
    if (empty($conf->browser->firefox))
    {
    	define('ROWS_1',1);
    	define('ROWS_2',2);
    	define('ROWS_3',3);
    	define('ROWS_4',4);
    	define('ROWS_5',5);
    	define('ROWS_6',6);
    	define('ROWS_7',7);
    	define('ROWS_8',8);
    	define('ROWS_9',9);
    }
    else
    {
    	define('ROWS_1',0);
    	define('ROWS_2',1);
    	define('ROWS_3',2);
    	define('ROWS_4',3);
    	define('ROWS_5',4);
    	define('ROWS_6',5);
    	define('ROWS_7',6);
    	define('ROWS_8',7);
    	define('ROWS_9',8);
    }


--------


.. _openconf-constants-multipleconstantdefinition:

OpenConf
^^^^^^^^

:ref:`multiple-constant-definition`, in modules/request.php:71. 

The constant is build according to the situation, in the part of the script (file request.php). This hides the actual origin of the value, but keeps the rest of the code simple. Just keep in mind that this constant may have different values.

.. code-block:: php

    if (isset($_GET['ocparams']) && !empty($_GET['ocparams'])) {
    		$params = '';
    		if (preg_match_all("/(\w+)--(\w+)_-/", $_GET['ocparams'], $matches)) {
    			foreach ($matches[1] as $idx => $m) {
    				if (($m != 'module') && ($m != 'action') && preg_match("/^[\w-]+$/", $m)) {
    					$params .= '&' . $m . '=' . urlencode($matches[2][$idx]);
    					$_GET[$m] = $matches[2][$idx];
    				}
    			}
    		}
    		unset($_GET['ocparams']);
    		define('OCC_SELF', $_SERVER['PHP_SELF'] . '?module=' . $_REQUEST['module'] . '&action=' . $_GET['action'] . $params);
    	} elseif (isset($_SERVER['REQUEST_URI']) && strstr($_SERVER['REQUEST_URI'], '?')) {
    		define('OCC_SELF', htmlspecialchars($_SERVER['REQUEST_URI']));
    	} elseif (isset($_SERVER['QUERY_STRING']) && strstr($_SERVER['QUERY_STRING'], '&')) {
    		define('OCC_SELF', $_SERVER['PHP_SELF'] . '?' . htmlspecialchars($_SERVER['QUERY_STRING']));
    	} else {
    		err('This server does not support REQUEST_URI or QUERY_STRING','Error');
    	}

Invalid Constant Name
=====================

.. _openemr-constants-invalidname:

OpenEMR
^^^^^^^

:ref:`invalid-constant-name`, in library/classes/InsuranceCompany.class.php:20. 

Either a copy/paste, or a generated definition file : the file contains 25 constants definition. The constant is not found in the rest of the code. 

.. code-block:: php

    define("INS_TYPE_OTHER_NON-FEDERAL_PROGRAMS", 10);

Wrong Optional Parameter
========================

.. _fuelcms-functions-wrongoptionalparameter:

FuelCMS
^^^^^^^

:ref:`wrong-optional-parameter`, in fuel/modules/fuel/helpers/validator_helper.php:78. 

The $regex parameter should really be first, as it is compulsory. Though, if this is a legacy function, it may be better to give regex a default value, such as empty string or null, and test it before using it.

.. code-block:: php

    if (!function_exists('regex'))
    {
    	function regex($var = null, $regex)
    	{
    		return preg_match('#'.$regex.'#', $var);
    	} 
    }


--------


.. _vanilla-functions-wrongoptionalparameter:

Vanilla
^^^^^^^

:ref:`wrong-optional-parameter`, in fuel/modules/fuel/helpers/validator_helper.php:78. 

Note the second parameter, $dropdown, which has no default value. It is relayed to the addDropdown method, which as no default value too. Since both methods are documented, we can see that they should be an addDropdown : null is probably a good idea, coupled with an explicit check on the actual value.

.. code-block:: php

    /**
         * Add a dropdown to the items array if it satisfies the $isAllowed condition.
         *
         * @param bool|string|array $isAllowed Either a boolean to indicate whether to actually add the item
         * or a permission string or array of permission strings (full match) to check.
         * @param DropdownModule $dropdown The dropdown menu to add.
         * @param string $key The item's key (for sorting and CSS targeting).
         * @param string $cssClass The dropdown wrapper's CSS class.
         * @param array|int $sort Either a numeric sort position or and array in the style: array('before|after', 'key').
         * @return NavModule $this The calling object.
         */
        public function addDropdownIf($isAllowed = true, $dropdown, $key = '', $cssClass = '', $sort = []) {
            if (!$this->isAllowed($isAllowed)) {
                return $this;
            } else {
                return $this->addDropdown($dropdown, $key, $cssClass, $sort);
            }
        }

One Variable String
===================

.. _tikiwiki-type-onevariablestrings:

Tikiwiki
^^^^^^^^

:ref:`one-variable-string`, in lib/wiki-plugins/wikiplugin_addtocart.php:228. 

Double-quotes are not needed here. If casting to string is important, the (string) would be more explicit.

.. code-block:: php

    foreach ($plugininfo['params'] as $key => $param) {
    		$default["$key"] = $param['default'];
    	}


--------


.. _nextcloud-type-onevariablestrings:

NextCloud
^^^^^^^^^

:ref:`one-variable-string`, in build/integration/features/bootstrap/BasicStructure.php:349. 

Both concatenations could be merged, independantly. If readability is important, why not put them inside curly brackets?

.. code-block:: php

    public static function removeFile($path, $filename) {
    		if (file_exists("$path" . "$filename")) {
    			unlink("$path" . "$filename");
    		}
    	}

Several Instructions On The Same Line
=====================================

.. _piwigo-structures-onelinetwoinstructions:

Piwigo
^^^^^^

:ref:`several-instructions-on-the-same-line`, in tools/triggers_list.php:993. 

There are two instructions on the line with the if(). Note that the condition is not followed by a bracketed block. When reviewing, it really seems that echo '<br>' and $f=0; are on the same block, but the second is indeed an unconditional expression. This is very difficult to spot. 

.. code-block:: php

    foreach ($trigger['files'] as $file)
          {
            if (!$f) echo '<br>'; $f=0;
            echo preg_replace('#\((.+)\)#', '(<i>$1</i>)', $file);
          }


--------


.. _tine20-structures-onelinetwoinstructions:

Tine20
^^^^^^

:ref:`several-instructions-on-the-same-line`, in tine20/Calendar/Controller/Event.php:1594. 

Here, $_event->attendee is saved in a local variable, then the property is destroyed. Same for $_event->notes; Strangely, a few lines above, the properties are unset on their own line. Unsetting properties leads to surprise bugs, and hidding the unset after ; makes it harder to spot.

.. code-block:: php

    $futurePersistentExceptionEvents->setRecurId($_event->getId());
                    unset($_event->recurid);
                    unset($_event->base_event_id);
                    foreach(array('attendee', 'notes', 'alarms') as $prop) {
                        if ($_event->{$prop} instanceof Tinebase_Record_RecordSet) {
                            $_event->{$prop}->setId(NULL);
                        }
                    }
                    $_event->exdate = $futureExdates;
    
                    $attendees = $_event->attendee; unset($_event->attendee);
                    $note = $_event->notes; unset($_event->notes);
                    $persistentExceptionEvent = $this->create($_event, $_checkBusyConflicts && $dtStartHasDiff);

Multiples Identical Case
========================

.. _sugarcrm-structures-multipledefinedcase:

SugarCrm
^^^^^^^^

:ref:`multiples-identical-case`, in modules/ModuleBuilder/MB/MBPackage.php:439. 

It takes a while to find the double 'required' case, but the executed code is actually the same, so this is dead code at worst. 

.. code-block:: php

    switch ($col) {
        case 'custom_module':
        	$installdefs['custom_fields'][$name]['module'] = $res;
        	break;
        case 'required':
        	$installdefs['custom_fields'][$name]['require_option'] = $res;
        	break;
        case 'vname':
        	$installdefs['custom_fields'][$name]['label'] = $res;
        	break;
        case 'required':
        	$installdefs['custom_fields'][$name]['require_option'] = $res;
        	break;
        case 'massupdate':
        	$installdefs['custom_fields'][$name]['mass_update'] = $res;
        	break;
        case 'comments':
        	$installdefs['custom_fields'][$name]['comments'] = $res;
        	break;
        case 'help':
        	$installdefs['custom_fields'][$name]['help'] = $res;
        	break;
        case 'len':
        	$installdefs['custom_fields'][$name]['max_size'] = $res;
        	break;
        default:
        	$installdefs['custom_fields'][$name][$col] = $res;
    }//switch


--------


.. _expressionengine-structures-multipledefinedcase:

ExpressionEngine
^^^^^^^^^^^^^^^^

:ref:`multiples-identical-case`, in ExpressionEngine_Core2.9.2/system/expressionengine/controllers/cp/admin_content.php:577. 

'deft_status' is doubled, with a fallthrough. This looks like some forgotten copy/paste. 

.. code-block:: php

    switch ($key){
    								case 'cat_group':
    								    //PHP code
    									break;
    								case 'status_group':
    								case 'field_group':
    								    //PHP code
    									break;
    								case 'deft_status':
    								case 'deft_status':
    								    //PHP code
    									break;
    								case 'search_excerpt':
    								    //PHP code
    									break;
    								case 'deft_category':
    								    //PHP code
    									break;
    								case 'blog_url':
    								case 'comment_url':
    								case 'search_results_url':
    								case 'rss_url':
    								    //PHP code
    									break;
    								default :
    								    //PHP code
    									break;
    							}

Switch Without Default
======================

.. _zencart-structures-switchwithoutdefault:

Zencart
^^^^^^^

:ref:`switch-without-default`, in admin/tax_rates.php:15. 

The 'action' is collected from $_GET and then, compared with various strings to handle the different actions to be taken. The default behavior is implicit here : if no 'action', display the initial form for taxes to be changed. This has to be understood as a general philosophy of ZenCart project, or by reading the rest of the HTML code. Adding a 'default' case here would help understand what happens in case 'action' is absent or unrecognized. 

.. code-block:: php

    $action = (isset($_GET['action']) ? $_GET['action'] : '');
    
      if (zen_not_null($action)) {
        switch ($action) {
          case 'insert':
            // PHP code 
            break;
          case 'save':
            // PHP code 
            break;
          case 'deleteconfirm':
            // PHP code
            break;
        }
      }
    ?> .... HTML code


--------


.. _traq-structures-switchwithoutdefault:

Traq
^^^^

:ref:`switch-without-default`, in src/Helpers/Ticketlist.php:311. 

The default case is actually processed after the switch, by the next if/then structure. The structure deals with the customFields, while the else deals with any unknown situations. This if/then could be wrapped in the 'default' case of switch, for consistent processing. The if/then condition would be hard to use as a 'case' (possible, though). 

.. code-block:: php

    public static function dataFor($column, $ticket)
        {
            switch ($column) {
                // Ticket ID column
                case 'ticket_id':
                    return $ticket['ticket_id'];
                    break;
    
                // Status column
                case 'status':
                case 'type':
                case 'component':
                case 'priority':
                case 'severity':
                    return $ticket[{$column}_name];
                    break;
    
                // Votes
                case 'votes':
                    return $ticket['votes'];
                    break;
            }
    
            // If we're still here, it may be a custom field
            if ($value = $ticket->customFieldValue($column)) {
                return $value->value;
            }
    
            // Nothing!
            return '';
        }

Nested Ternary
==============

.. _spip-structures-nestedternary:

SPIP
^^^^

:ref:`nested-ternary`, in ecrire/inc/utils.php:2648. 

Interesting usage of both if/then, for the flow control, and ternary, for data process. Even on multiple lines, nested ternaries are quite hard to read. 

.. code-block:: php

    // le script de l'espace prive
    	// Mettre a "index.php" si DirectoryIndex ne le fait pas ou pb connexes:
    	// les anciens IIS n'acceptent pas les POST sur ecrire/ (#419)
    	// meme pb sur thttpd cf. http://forum.spip.net/fr_184153.html
    	if (!defined('_SPIP_ECRIRE_SCRIPT')) {
    		define('_SPIP_ECRIRE_SCRIPT', (empty($_SERVER['SERVER_SOFTWARE']) ? '' :
    			preg_match(',IIS|thttpd,', $_SERVER['SERVER_SOFTWARE']) ?
    				'index.php' : ''));
    	}


--------


.. _zencart-structures-nestedternary:

Zencart
^^^^^^^

:ref:`nested-ternary`, in ecrire/inc/utils.php:2648. 

No more than one level of nesting for this ternary call, yet it feels a lot more, thanks to the usage of arrayed properties, constants, and functioncalls. 

.. code-block:: php

    $lc_text .= '<br />' . (zen_get_show_product_switch($listing->fields['products_id'], 'ALWAYS_FREE_SHIPPING_IMAGE_SWITCH') ? (zen_get_product_is_always_free_shipping($listing->fields['products_id']) ? TEXT_PRODUCT_FREE_SHIPPING_ICON . '<br />' : '') : '');

Empty Try Catch
===============

.. _livezilla-structures-emptytrycatch:

LiveZilla
^^^^^^^^^

:ref:`empty-try-catch`, in livezilla/_lib/trdp/Zend/Mail/Protocol/Pop3.php:237. 

This is an aptly commented empty try/catch : the emited exception is extra check for a Zend Mail Protocol Exception. Hopefully, the Zend_Mail_Protocol_Exception only covers a already-closed situation. Anyhow, this should be logged for later diagnostic. 

.. code-block:: php

    public function logout()
        {
            if (!$this->_socket) {
                return;
            }
    
            try {
                $this->request('QUIT');
            } catch (Zend_Mail_Protocol_Exception $e) {
                // ignore error - we're closing the socket anyway
            }
    
            fclose($this->_socket);
            $this->_socket = null;
        }


--------


.. _mautic-structures-emptytrycatch:

Mautic
^^^^^^

:ref:`empty-try-catch`, in livezilla/_lib/trdp/Zend/Mail/Protocol/Pop3.php:237. 

Removing a file : if the file is not 'deleted' by the method call, but raises an error, it is hidden. When file destruction is impossible because the file is already destroyed (or missing), this is well. If the file couldn't be destroyed because of missing writing privileges, hiding this error will have serious consequences. 

.. code-block:: php

    /**
         * @param string $fileName
         */
        public function removeFile($fileName)
        {
            try {
                $path = $this->getPath($fileName);
                $this->filePathResolver->delete($path);
            } catch (FileIOException $e) {
            }
        }

Dangling Array References
=========================

.. _typo3-structures-danglingarrayreferences:

Typo3
^^^^^

:ref:`dangling-array-references`, in typo3/sysext/impexp/Classes/ImportExport.php:322. 

foreach() reads $lines into $r, and augment those lines. By the end, the $r variable is not unset. Yet, several lines later, in the same method but with different conditions, another loop reuse the variable $r. If is_array($this->dat['header']['pagetree'] and is_array($this->remainHeader['records']) are arrays at the same moment, then both loops are called, and they share the same reference. Values of the latter array will end up in the formar. 

.. code-block:: php

    if (is_array($this->dat['header']['pagetree'])) {
                reset($this->dat['header']['pagetree']);
                $lines = [];
                $this->traversePageTree($this->dat['header']['pagetree'], $lines);
    
                $viewData['dat'] = $this->dat;
                $viewData['update'] = $this->update;
                $viewData['showDiff'] = $this->showDiff;
                if (!empty($lines)) {
                    foreach ($lines as &$r) {
                        $r['controls'] = $this->renderControls($r);
                        $r['fileSize'] = GeneralUtility::formatSize($r['size']);
                        $r['message'] = ($r['msg'] && !$this->doesImport ? '<span class=text-danger>' . htmlspecialchars($r['msg']) . '</span>' : '');
                    }
                    $viewData['pagetreeLines'] = $lines;
                } else {
                    $viewData['pagetreeLines'] = [];
                }
            }
            // Print remaining records that were not contained inside the page tree:
            if (is_array($this->remainHeader['records'])) {
                $lines = [];
                if (is_array($this->remainHeader['records']['pages'])) {
                    $this->traversePageRecords($this->remainHeader['records']['pages'], $lines);
                }
                $this->traverseAllRecords($this->remainHeader['records'], $lines);
                if (!empty($lines)) {
                    foreach ($lines as &$r) {
                        $r['controls'] = $this->renderControls($r);
                        $r['fileSize'] = GeneralUtility::formatSize($r['size']);
                        $r['message'] = ($r['msg'] && !$this->doesImport ? '<span class=text-danger>' . htmlspecialchars($r['msg']) . '</span>' : '');
                    }
                    $viewData['remainingRecords'] = $lines;
                }
            }


--------


.. _sugarcrm-structures-danglingarrayreferences:

SugarCrm
^^^^^^^^

:ref:`dangling-array-references`, in typo3/sysext/impexp/Classes/ImportExport.php:322. 

There are two nested foreach here : they both have referenced blind variables. The second one uses $data, but never changes it. Yet, it is reused the next round in the first loop, leading to pollution from the first rows of $this->_parser->data into the lasts. This may happen even if $data is not modified explicitely : in fact, it will be modified the next call to foreach($row as ...), for each element in $row. 

.. code-block:: php

    foreach ($this->_parser->data as &$row) {
                    foreach ($row as &$data) {
                        $len = strlen($data);
                        // check if it begins and ends with single quotes
                        // if it does, then it double quotes may not be the enclosure
                        if ($len>=2 && $data[0] == " && $data[$len-1] == ") {
                            $beginEndWithSingle = true;
                            break;
                        }
                    }
                    if ($beginEndWithSingle) {
                        break;
                    }
                    $depth++;
                    if ($depth > $this->_max_depth) {
                        break;
                    }
                }

Var Keyword
===========

.. _xataface-classes-oldstylevar:

xataface
^^^^^^^^

:ref:`var-keyword`, in SQL/Parser/wrapper.php:24. 

With the usage of var and a first method bearing the name of the class, this is PHP 4 code that is still in use. 

.. code-block:: php

    class SQL_Parser_wrapper {
    	
    	var $_data;
    	var $_tableLookup;
    	var $_parser;
    	
    	function SQL_Parser_wrapper(&$data, $dialect='MySQL'){

Wrong Number Of Arguments
=========================

.. _xataface-functions-wrongnumberofarguments:

xataface
^^^^^^^^

:ref:`wrong-number-of-arguments`, in actions/existing_related_record.php:130. 

df_display() actually requires only 2 arguments, while three are provided. The last argument is completely ignored. df_display() is called in a total of 9 places : this now looks like an API change that left many calls untouched.

.. code-block:: php

    df_display($context, $template, true);
    
    // in public-api.php :
    function df_display($context, $template_name){
    	import( 'Dataface/SkinTool.php');
    	$st = Dataface_SkinTool::getInstance();
    	
    	return $st->display($context, $template_name);
    }

Useless Return
==============

.. _thinkphp-functions-uselessreturn:

ThinkPHP
^^^^^^^^

:ref:`useless-return`, in library/think/Request.php:2121. 

__set() doesn't need a return, unlike __get().

.. code-block:: php

    public function __set($name, $value)
        {
            return $this->param[$name] = $value;
        }


--------


.. _vanilla-functions-uselessreturn:

Vanilla
^^^^^^^

:ref:`useless-return`, in applications/dashboard/views/attachments/attachment.php:14. 

The final 'return' is useless : return void (here, return without argument), is the same as returning null, unless the 'void' return type is used. The other return, is in the two conditions, is important to skip the end of the functioncall.

.. code-block:: php

    function writeAttachment($attachment) {
    
            $customMethod = AttachmentModel::getWriteAttachmentMethodName($attachment['Type']);
            if (function_exists($customMethod)) {
                if (val('Error', $attachment)) {
                    writeErrorAttachment($attachment);
                    return;
                }
                $customMethod($attachment);
            } else {
                trace($customMethod, 'Write Attachment method not found');
                trace($attachment, 'Attachment');
            }
            return;
        }

Unpreprocessed Values
=====================

.. _zurmo-structures-unpreprocessed:

Zurmo
^^^^^

:ref:`unpreprocessed-values`, in app/protected/core/utils/ZurmoTranslationServerUtil.php:79. 

It seems that a simple concatenation could be used here. There is another call to this expression in the code, and a third that uses 'PATCH_VERSION' on top of the two others.

.. code-block:: php

    join('.', array(MAJOR_VERSION, MINOR_VERSION))


--------


.. _piwigo-structures-unpreprocessed:

Piwigo
^^^^^^

:ref:`unpreprocessed-values`, in include/random_compat/random.php:34. 

PHP_VERSION is actually build with PHP_MAJOR_VERSION, PHP_MINOR_VERSION and PHP_RELEASE_VERSION. There is also a compact version : PHP_VERSION_ID

.. code-block:: php

    explode('.', PHP_VERSION);

Logical Should Use Symbolic Operators
=====================================

.. _cleverstyle-php-logicalinletters:

Cleverstyle
^^^^^^^^^^^

:ref:`logical-should-use-symbolic-operators`, in modules/Uploader/Mime/Mime.php:171. 

$extension is assigned with the results of pathinfo($reference_name, PATHINFO_EXTENSION) and ignores static::hasExtension($extension). The same expression, placed in a condition (like an if), would assign a value to $extension and use another for the condition itself. Here, this code is only an expression in the flow.

.. code-block:: php

    $extension = pathinfo($reference_name, PATHINFO_EXTENSION) and static::hasExtension($extension);


--------


.. _openconf-php-logicalinletters:

OpenConf
^^^^^^^^

:ref:`logical-should-use-symbolic-operators`, in chair/export.inc:143. 

In this context, the priority of execution is used on purpose; $coreFile only collect the temporary name of the export file, and when this name is empty, then the second operand of OR is executed, though never collected. Since this second argument is a 'die', its return value is lost, but the initial assignation is never used anyway. 

.. code-block:: php

    $coreFile = tempnam('/tmp/', 'ocexport') or die('could not generate Excel file (6)')

Deep Definitions
================

.. _dolphin-functions-deepdefinitions:

Dolphin
^^^^^^^

:ref:`deep-definitions`, in wp-admin/includes/misc.php:74. 

The ConstructHiddenValues function builds the ConstructHiddenSubValues function. Thus, ConstructHiddenValues can only be called once. 

.. code-block:: php

    function ConstructHiddenValues($Values)
    {
        /**
         *    Recursive function, processes multidimensional arrays
         *
         * @param string $Name  Full name of array, including all subarrays' names
         *
         * @param array  $Value Array of values, can be multidimensional
         *
         * @return string    Properly consctructed <input type="hidden"...> tags
         */
        function ConstructHiddenSubValues($Name, $Value)
        {
            if (is_array($Value)) {
                $Result = "";
                foreach ($Value as $KeyName => $SubValue) {
                    $Result .= ConstructHiddenSubValues("{$Name}[{$KeyName}]", $SubValue);
                }
            } else // Exit recurse
            {
                $Result = "<input type=\"hidden\" name=\"" . htmlspecialchars($Name) . "\" value=\"" . htmlspecialchars($Value) . "\" />\n";
            }
    
            return $Result;
        }
    
        /* End of ConstructHiddenSubValues function */
    
        $Result = '';
        if (is_array($Values)) {
            foreach ($Values as $KeyName => $Value) {
                $Result .= ConstructHiddenSubValues($KeyName, $Value);
            }
        }
    
        return $Result;
    }

Repeated print()
================

.. _edusoho-structures-repeatedprint:

Edusoho
^^^^^^^

:ref:`repeated-print()`, in app/check.php:71. 

All echo may be merged into one : do this by turning the ; and . into ',', and removing the superfluous echo. Also, echo_style may be turned into a non-display function, returning the build style, rather than echoing it to the output.

.. code-block:: php

    echo PHP_EOL;
    echo_style('title', 'Note');
    echo '  The command console could use a different php.ini file'.PHP_EOL;
    echo_style('title', '~~~~');
    echo '  than the one used with your web server. To be on the'.PHP_EOL;
    echo '      safe side, please check the requirements from your web'.PHP_EOL;
    echo '      server using the ';
    echo_style('yellow', 'web/config.php');
    echo ' script.'.PHP_EOL;
    echo PHP_EOL;


--------


.. _humo-gen-structures-repeatedprint:

HuMo-Gen
^^^^^^^^

:ref:`repeated-print()`, in menu.php:71. 

Simply calling print once is better than three times. Here too, echo usage would reduce the amount of memory allocation due to concatenation prior display.

.. code-block:: php

    print '<input type=text name=quicksearch value=.$quicksearch. size=10 '.$pattern.' title=.__(Minimum:).$min_chars.__(characters).>';
    			print ' <input type=submit value=.__(Search).>';
    		print </form>;

Useless Unset
=============

.. _tine20-structures-uselessunset:

Tine20
^^^^^^

:ref:`useless-unset`, in tine20/Felamimail/Controller/Message.php:542. 

$_rawContent is unset after being sent to the stream. The variable is a parameter, and will be freed at the end of the call of the method. No need to do it explicitly.

.. code-block:: php

    protected function _createMimePart($_rawContent, $_partStructure)
        {
            if (Tinebase_Core::isLogLevel(Zend_Log::TRACE)) Tinebase_Core::getLogger()->trace(__METHOD__ . '::' . __LINE__ . ' Content: ' . $_rawContent);
            
            $stream = fopen(php://temp, 'r+');
            fputs($stream, $_rawContent);
            rewind($stream);
            
            unset($_rawContent);
            //..... More code, no usage of $_rawContent
        }


--------


.. _typo3-structures-uselessunset:

Typo3
^^^^^

:ref:`useless-unset`, in typo3/sysext/frontend/Classes/Page/PageRepository.php:708. 

$row is unset under certain conditions : here, we can read it in the comments. Eventually, the $row will be returned, and turned into a NULL, by default. This will also create a notice in the logs. Here, the best would be to set a null value, instead of unsetting the variable.

.. code-block:: php

    public function getRecordOverlay($table, $row, $sys_language_content, $OLmode = '')
        {
    //....  a lot more code, with usage of $row, and several unset($row)
    //...... Reduced for simplicity
                        } else {
                            // When default language is displayed, we never want to return a record carrying
                            // another language!
                            if ($row[$GLOBALS['TCA'][$table]['ctrl']['languageField']] > 0) {
                                unset($row);
                            }
                        }
                    }
                }
            }
            foreach ($GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_page.php']['getRecordOverlay'] ?? [] as $className) {
                $hookObject = GeneralUtility::makeInstance($className);
                if (!$hookObject instanceof PageRepositoryGetRecordOverlayHookInterface) {
                    throw new \UnexpectedValueException($className . ' must implement interface ' . PageRepositoryGetRecordOverlayHookInterface::class, 1269881659);
                }
                $hookObject->getRecordOverlay_postProcess($table, $row, $sys_language_content, $OLmode, $this);
            }
            return $row;
        }

Buried Assignation
==================

.. _xoops-structures-buriedassignation:

XOOPS
^^^^^

:ref:`buried-assignation`, in htdocs/image.php:170. 

Classic iffectation : the condition also collects the needed value to process the drawing. This is very common in PHP, and the Yoda condition, with its constant on the left, shows that extra steps were taken to strengthen that piece of code.  

.. code-block:: php

    if (0 < ($radius = $radii[2] * $q)) { // left bottom
            imagearc($workingImage, $radius - 1, $workingHeight - $radius, $radius * 2, $radius * 2, 90, 180, $alphaColor);
            imagefilltoborder($workingImage, 0, $workingHeight - 1, $alphaColor, $alphaColor);
        }


--------


.. _mautic-structures-buriedassignation:

Mautic
^^^^^^

:ref:`buried-assignation`, in app/bundles/CoreBundle/Controller/ThemeController.php:47. 

The setting of the variable $cancelled is fairly hidden here, with its extra operator !. The operator is here for the condition, as $cancelled needs the 'cancellation' state, while the condition needs the contrary. Note also that isset() could be moved out of this condition, and made the result easier to read.

.. code-block:: php

    $form        = $this->get('form.factory')->create('theme_upload', [], ['action' => $action]);
    
            if ($this->request->getMethod() == 'POST') {
                if (isset($form) && !$cancelled = $this->isFormCancelled($form)) {
                    if ($this->isFormValid($form)) {
                        $fileData = $form['file']->getData();

No array_merge() In Loops
=========================

.. _tine20-performances-arraymergeinloops:

Tine20
^^^^^^

:ref:`no-array\_merge()-in-loops`, in tine20/Tinebase/User/Ldap.php:670. 

Classic example of array_merge() in loop : here, the attributures should be collected in a local variable, and then merged in one operation, at the end. That includes the attributes provided before the loop, and the array provided after the loop. 
Note that the order of merge will be the same when merging than when collecting the arrays.

.. code-block:: php

    $attributes = array_values($this->_rowNameMapping);
            foreach ($this->_ldapPlugins as $plugin) {
                $attributes = array_merge($attributes, $plugin->getSupportedAttributes());
            }
    
            $attributes = array_merge($attributes, $this->_additionalLdapAttributesToFetch);

Useless Parenthesis
===================

.. _mautic-structures-uselessparenthesis:

Mautic
^^^^^^

:ref:`useless-parenthesis`, in code/app/bundles/EmailBundle/Controller/AjaxController.php:85. 

Parenthesis are useless around $progress[1], and around the division too. 

.. code-block:: php

    $dataArray['percent'] = ($progress[1]) ? ceil(($progress[0] / $progress[1]) * 100) : 100;


--------


.. _woocommerce-structures-uselessparenthesis:

Woocommerce
^^^^^^^^^^^

:ref:`useless-parenthesis`, in code/app/bundles/EmailBundle/Controller/AjaxController.php:85. 

Parenthesis are useless for calculating $discount_percent, as it is a divisition. Moreover, it is not needed with $discount, (float) applies to the next element, but it does make the expression more readable. 

.. code-block:: php

    if ( wc_prices_include_tax() ) {
    				$discount_percent = ( wc_get_price_including_tax( $cart_item['data'] ) * $cart_item_qty ) / WC()->cart->subtotal;
    			} else {
    				$discount_percent = ( wc_get_price_excluding_tax( $cart_item['data'] ) * $cart_item_qty ) / WC()->cart->subtotal_ex_tax;
    			}
    			$discount = ( (float) $this->get_amount() * $discount_percent ) / $cart_item_qty;

Altering Foreach Without Reference
==================================

.. _wordpress-structures-alteringforeachwithoutreference:

WordPress
^^^^^^^^^

:ref:`altering-foreach-without-reference`, in wp-admin/includes/misc.php:74. 

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    $markerdata = explode( "\n", implode( '', file( $filename ) ) );

No Parenthesis For Language Construct
=====================================

.. _phpdocumentor-structures-noparenthesisforlanguageconstruct:

Phpdocumentor
^^^^^^^^^^^^^

:ref:`no-parenthesis-for-language-construct`, in /src/Application/Renderer/Router/StandardRouter.php:55. 

No need for parenthesis with require(). instanceof has a higher precedence than return anyway. 

.. code-block:: php

    $this[] = new Rule(function ($node) { return ($node instanceof NamespaceDescriptor); }, $namespaceGenerator);


--------


.. _phpmyadmin-structures-noparenthesisforlanguageconstruct:

phpMyAdmin
^^^^^^^^^^

:ref:`no-parenthesis-for-language-construct`, in /db_datadict.php:170. 

Not only echo() doesn't use any parenthesis, but this syntax gives the illusion that echo() only accepts one argument, while it actually accepts an arbitrary number of argument.

.. code-block:: php

    echo (($row['Null'] == 'NO') ? __('No') : __('Yes'))

Use Constant As Arguments
=========================

.. _tikiwiki-functions-useconstantasarguments:

Tikiwiki
^^^^^^^^

:ref:`use-constant-as-arguments`, in lib/language/Language.php:112. 

E_WARNING is a valid constant, but PHP documentation for trigger_error() explains that E_USER constants should be used. 

.. code-block:: php

    trigger_error("Octal or hexadecimal string '" . $match[1] . "' not supported", E_WARNING)


--------


.. _shopware-functions-useconstantasarguments:

shopware
^^^^^^^^

:ref:`use-constant-as-arguments`, in engine/Shopware/Plugins/Default/Core/Debug/Components/EventCollector.php:106. 

One example where code review reports errors where unit tests don't : array_multisort actually requires sort order first (SORT_ASC or SORT_DESC), then sort flags (such as SORT_NUMERIC). Here, with SORT_DESC = 3 and SORT_NUMERIC = 1, PHP understands it as the coders expects it. The same error is repeated six times in the code. 

.. code-block:: php

    array_multisort($order, SORT_NUMERIC, SORT_DESC, $this->results)

Could Be Static
===============

.. _dolphin-structures-couldbestatic:

Dolphin
^^^^^^^

:ref:`could-be-static`, in inc/utils.inc.php:673. 

Dolphin pro relies on HTMLPurifier to handle cleaning of values : it is used to prevent xss threat. In this method, oHtmlPurifier is first checked, and if needed, created. Since creation is long and costly, it is only created once. Once the object is created, it is stored as a global to be accessible at the next call of the method. In fact, oHtmlPurifier is never used outside this method, so it could be turned into a 'static' variable, and prevent other methods to modify it. This is a typical example of variable that could be static instead of global. 

.. code-block:: php

    function clear_xss($val)
    {
        // HTML Purifier plugin
        global $oHtmlPurifier;
        if (!isset($oHtmlPurifier) && !$GLOBALS['logged']['admin']) {
    
            require_once(BX_DIRECTORY_PATH_PLUGINS . 'htmlpurifier/HTMLPurifier.standalone.php');
    
    /..../
    
            $oHtmlPurifier = new HTMLPurifier($oConfig);
        }
    
        if (!$GLOBALS['logged']['admin']) {
            $val = $oHtmlPurifier->purify($val);
        }
    
        $oZ = new BxDolAlerts('system', 'clear_xss', 0, 0,
            array('oHtmlPurifier' => $oHtmlPurifier, 'return_data' => &$val));
        $oZ->alert();
    
        return $val;
    }


--------


.. _contao-structures-couldbestatic:

Contao
^^^^^^

:ref:`could-be-static`, in system/helper/functions.php:184. 

$arrScanCache is a typical cache variables. It is set as global for persistence between calls. If it contains an already stored answer, it is returned immediately. If it is not set yet, it is then filled with a value, and later reused. This global could be turned into static, and avoid pollution of global space. 

.. code-block:: php

    function scan($strFolder, $blnUncached=false)
    {
    	global $arrScanCache;
    
    	// Add a trailing slash
    	if (substr($strFolder, -1, 1) != '/')
    	{
    		$strFolder .= '/';
    	}
    
    	// Load from cache
    	if (!$blnUncached && isset($arrScanCache[$strFolder]))
    	{
    		return $arrScanCache[$strFolder];
    	}
    	$arrReturn = array();
    
    	// Scan directory
    	foreach (scandir($strFolder) as $strFile)
    	{
    		if ($strFile == '.' || $strFile == '..')
    		{
    			continue;
    		}
    
    		$arrReturn[] = $strFile;
    	}
    
    	// Cache the result
    	if (!$blnUncached)
    	{
    		$arrScanCache[$strFolder] = $arrReturn;
    	}
    
    	return $arrReturn;
    }

Could Use Short Assignation
===========================

.. _churchcrm-structures-coulduseshortassignation:

ChurchCRM
^^^^^^^^^

:ref:`could-use-short-assignation`, in src/ChurchCRM/utils/GeoUtils.php:74. 

Sometimes, the variable is on the other side of the operator.

.. code-block:: php

    $distance = 0.6213712 * $distance;


--------


.. _thelia-structures-coulduseshortassignation:

Thelia
^^^^^^

:ref:`could-use-short-assignation`, in local/modules/Tinymce/Resources/js/tinymce/filemanager/include/utils.php:70. 

/= is rare, but it definitely could be used here.

.. code-block:: php

    $size = $size / 1024;

Should Typecast
===============

.. _openconf-type-shouldtypecast:

OpenConf
^^^^^^^^

:ref:`should-typecast`, in /author/upload.php:62. 

This is another exact example. 

.. code-block:: php

    intval($_POST['pid']);

preg_replace With Option e
==========================

.. _edusoho-structures-pregoptione:

Edusoho
^^^^^^^

:ref:`preg\_replace-with-option-e`, in vendor_user/uc_client/lib/uccode.class.php:32. 

This call extract text between [code] tags, then process it with $this->codedisp() and nest it again in the original string. preg_replace_callback() is a drop-in replacement for this piece of code. 

.. code-block:: php

    $message = preg_replace("/\s*\[code\](.+?)\[\/code\]\s*/ies", "$this->codedisp('\1')", $message);

Relay Function
==============

.. _teampass-functions-relayfunction:

TeamPass
^^^^^^^^

:ref:`relay-function`, in includes/libraries/Goodby/CSV/Import/Standard/Interpreter.php:88. 

This example puts actually a name on the events : this method 'delegate' and it does it in the smallest amount of possible work, being given all the arguments. 

.. code-block:: php

    /**
         * delegate to observer
         *
         * @param $observer
         * @param $line
         */
        private function delegate($observer, $line)
        {
            call_user_func($observer, $line);
        }


--------


.. _spip-functions-relayfunction:

SPIP
^^^^

:ref:`relay-function`, in ecrire/inc/json.php:73. 

var2js() acts as an alternative for json_encode(). Yet, it used to be directly called by the framework's code and difficult to change. With the advent of json_encode, the native function has been used, and even, a compatibility tool was set up. Thus, the relay function. 

.. code-block:: php

    if (!function_exists('json_encode')) {
    	function json_encode($v) {
    		return var2js($v);
    	}
    }

Timestamp Difference
====================

.. _zurmo-structures-timestampdifference:

Zurmo
^^^^^

:ref:`timestamp-difference`, in app/protected/modules/import/jobs/ImportCleanupJob.php:73. 

This is wrong twice a year, in countries that has day-ligth saving time. One of the weeks will be too short, and the other will be too long. 

.. code-block:: php

    /**
             * Get all imports where the modifiedDateTime was more than 1 week ago.  Then
             * delete the imports.
             * (non-PHPdoc)
             * @see BaseJob::run()
             */
            public function run()
            {
                $oneWeekAgoTimeStamp = DateTimeUtil::convertTimestampToDbFormatDateTime(time() - 60 * 60 *24 * 7);


--------


.. _shopware-structures-timestampdifference:

shopware
^^^^^^^^

:ref:`timestamp-difference`, in engine/Shopware/Controllers/Backend/Newsletter.php:150. 

When daylight saving strike, the email may suddenly be locked for 1 hour minus 30 seconds ago. The lock will be set for the rest of the hour, until the server catch up. 

.. code-block:: php

    // Check lock time. Add a buffer of 30 seconds to the lock time (default request time)
                if (!empty($mailing['locked']) && strtotime($mailing['locked']) > time() - 30) {
                    echo "Current mail: '" . $subjectCurrentMailing . "'\n";
                    echo "Wait " . (strtotime($mailing['locked']) + 30 - time()) . " seconds ...\n";
                    return;
                }

Wrong Parameter Type
====================

.. _zencart-php-internalparametertype:

Zencart
^^^^^^^

:ref:`wrong-parameter-type`, in admin/includes/header.php:180. 

setlocale() may be called with null or '' (empty string), and will set values from the environnement. When called with "0" (the string), it only reports the current setting. Using an integer is probably undocumented behavior, and falls back to the zero string. 

.. code-block:: php

    $loc = setlocale(LC_TIME, 0);
            if ($loc !== FALSE) echo ' - ' . $loc; //what is the locale in use?

Wrong fopen() Mode
==================

.. _tikiwiki-php-fopenmode:

Tikiwiki
^^^^^^^^

:ref:`wrong-fopen()-mode`, in lib/tikilib.php:6777. 

This fopen() mode doesn't exists. Use 'w' instead.

.. code-block:: php

    fopen('php://temp', 'rw');


--------


.. _humo-gen-php-fopenmode:

HuMo-Gen
^^^^^^^^

:ref:`wrong-fopen()-mode`, in include/phprtflite/lib/PHPRtfLite/StreamOutput.php:77. 

This fopen() mode doesn't exists. Use 'w' instead.

.. code-block:: php

    fopen($this->_filename, 'wr', false)

Use random_int()
================

.. _thelia-php-betterrand:

Thelia
^^^^^^

:ref:`use-random\_int()`, in core/lib/Thelia/Tools/TokenProvider.php:151. 

The whole function may be replaced by random_int(), as it generates random tokens. This needs an extra layer of hashing, to get a long and string results. 

.. code-block:: php

    /**
         * @return string
         */
        protected static function getComplexRandom()
        {
            $firstValue = (float) (mt_rand(1, 0xFFFF) * rand(1, 0x10001));
            $secondValues = (float) (rand(1, 0xFFFF) * mt_rand(1, 0x10001));
    
            return microtime() . ceil($firstValue / $secondValues) . uniqid();
        }


--------


.. _fuelcms-php-betterrand:

FuelCMS
^^^^^^^

:ref:`use-random\_int()`, in fuel/modules/fuel/libraries/Fuel.php:235. 

Security tokens should be build with a CSPRNG source. uniqid() is based on time, and though it changes anytime (sic), it is easy to guess. Those days, it looks like '5b1262e74dbb9'; 

.. code-block:: php

    $this->installer->change_config('config', '$config[\'encryption_key\'] = \'\';', '$config[\'encryption_key\'] = \''.md5(uniqid()).'\';');

Identical Conditions
====================

.. _wordpress-structures-identicalconditions:

WordPress
^^^^^^^^^

:ref:`identical-conditions`, in wp-admin/theme-editor.php:247. 

The condition checks first if $has_templates or $theme->parent(), and one of the two is sufficient to be valid. Then, it checks again that $theme->parent() is activated with &&. This condition may be reduced by calling $theme->parent(), as $has_template is unused here.

.. code-block:: php

    <?php if ( ( $has_templates || $theme->parent() ) && $theme->parent() ) : ?>


--------


.. _dolibarr-structures-identicalconditions:

Dolibarr
^^^^^^^^

:ref:`identical-conditions`, in htdocs/core/lib/files.lib.php:2052. 

Better check twice that $modulepart is really 'apercusupplier_invoice'.

.. code-block:: php

    $modulepart == 'apercusupplier_invoice' || $modulepart == 'apercusupplier_invoice'


--------


.. _mautic-structures-identicalconditions:

Mautic
^^^^^^

:ref:`identical-conditions`, in app/bundles/CoreBundle/Views/Standard/list.html.php:47. 

When the line is long, it tends to be more and more difficult to review the values. Here, one of the two first is too many.

.. code-block:: php

    !empty($permissions[$permissionBase . ':deleteown']) || !empty($permissions[$permissionBase . ':deleteown']) || !empty($permissions[$permissionBase . ':delete'])

No Choice
=========

.. _nextcloud-structures-nochoice:

NextCloud
^^^^^^^^^

:ref:`no-choice`, in build/integration/features/bootstrap/FilesDropContext.php:71. 

Token is checked, but processed in the same way each time. This actual check is done twice, in the same class, in the method droppingFileWith(). 

.. code-block:: php

    public function creatingFolderInDrop($folder) {
    		$client = new Client();
    		$options = [];
    		if (count($this->lastShareData->data->element) > 0){
    			$token = $this->lastShareData->data[0]->token;
    		} else {
    			$token = $this->lastShareData->data[0]->token;
    		}
    		$base = substr($this->baseUrl, 0, -4);
    		$fullUrl = $base . '/public.php/webdav/' . $folder;
    
    		$options['auth'] = [$token, ''];


--------


.. _zencart-structures-nochoice:

Zencart
^^^^^^^

:ref:`no-choice`, in admin/includes/functions/html_output.php:179. 

At least, it always choose the most secure way : use SSL.

.. code-block:: php

    if ($usessl) {
            $form .= zen_href_link($action, $parameters, 'NONSSL');
          } else {
            $form .= zen_href_link($action, $parameters, 'NONSSL');
          }

Should Use Coalesce
===================

.. _churchcrm-php-shouldusecoalesce:

ChurchCRM
^^^^^^^^^

:ref:`should-use-coalesce`, in src/ChurchCRM/Service/FinancialService.php:597. 

ChurchCRM features 5 old style ternary operators, which are all in this SQL query. ChurchCRM requires PHP 7.0, so a simple code review could remove them all.

.. code-block:: php

    $sSQL = "INSERT INTO pledge_plg
                        (plg_famID,
                        plg_FYID, 
                        plg_date, 
                        plg_amount,
                        plg_schedule, 
                        plg_method, 
                        plg_comment, 
                        plg_DateLastEdited, 
                        plg_EditedBy, 
                        plg_PledgeOrPayment, 
                        plg_fundID, 
                        plg_depID, 
                        plg_CheckNo, 
                        plg_scanString, 
                        plg_aut_ID, 
                        plg_NonDeductible, 
                        plg_GroupKey)
                        VALUES ('".
              $payment->FamilyID."','".
              $payment->FYID."','".
              $payment->Date."','".
              $Fund->Amount."','".
              (isset($payment->schedule) ? $payment->schedule : 'NULL')."','".
              $payment->iMethod."','".
              $Fund->Comment."','".
              date('YmdHis')."',".
              $_SESSION['user']->getId().",'".
              $payment->type."',".
              $Fund->FundID.','.
              $payment->DepositID.','.
              (isset($payment->iCheckNo) ? $payment->iCheckNo : 'NULL').",'".
              (isset($payment->tScanString) ? $payment->tScanString : 'NULL')."','".
              (isset($payment->iAutID) ? $payment->iAutID : 'NULL')."','".
              (isset($Fund->NonDeductible) ? $Fund->NonDeductible : 'NULL')."','".
              $sGroupKey."')";


--------


.. _cleverstyle-php-shouldusecoalesce:

Cleverstyle
^^^^^^^^^^^

:ref:`should-use-coalesce`, in modules/Feedback/index.php:37. 

Cleverstyle nests ternary operators when selecting default values. Here, moving some of them to ?? will reduce the code complexity and make it more readable. Cleverstyle requires PHP 7.0 or more recent.

.. code-block:: php

    $Page->content(
    	h::{'cs-form form'}(
    		h::{'section.cs-feedback-form article'}(
    			h::{'header h2.cs-text-center'}($L->Feedback).
    			h::{'table.cs-table[center] tr| td'}(
    				[
    					h::{'cs-input-text input[name=name][required]'}(
    						[
    							'placeholder' => $L->feedback_name,
    							'value'       => $User->user() ? $User->username() : (isset($_POST['name']) ? $_POST['name'] : '')
    						]
    					),
    					h::{'cs-input-text input[type=email][name=email][required]'}(
    						[
    							'placeholder' => $L->feedback_email,
    							'value'       => $User->user() ? $User->email : (isset($_POST['email']) ? $_POST['email'] : '')
    						]
    					),
    					h::{'cs-textarea[autosize] textarea[name=text][required]'}(
    						[
    							'placeholder' => $L->feedback_text,
    							'value'       => isset($_POST['text']) ? $_POST['text'] : ''
    						]
    					),
    					h::{'cs-button button[type=submit]'}($L->feedback_send)
    				]
    			)
    		)
    	)
    );

If With Same Conditions
=======================

.. _phpmyadmin-structures-ifwithsameconditions:

phpMyAdmin
^^^^^^^^^^

:ref:`if-with-same-conditions`, in libraries/classes/Response.php:345. 

The first test on $this->_isSuccess settles the situation with _JSON. Then, a second check is made. Both could be merged, also the second one is fairly long (not shown). 

.. code-block:: php

    if ($this->_isSuccess) {
                $this->_JSON['success'] = true;
            } else {
                $this->_JSON['success'] = false;
                $this->_JSON['error']   = $this->_JSON['message'];
                unset($this->_JSON['message']);
            }
    
            if ($this->_isSuccess) {


--------


.. _phpdocumentor-structures-ifwithsameconditions:

Phpdocumentor
^^^^^^^^^^^^^

:ref:`if-with-same-conditions`, in src/phpDocumentor/Transformer/Command/Project/TransformCommand.php:239. 

$templates is extracted from $input. If it is empty, a second source is polled. Finally, if nothing has worked, a default value is used ('clean'). In this case, each attempt is an alternative solution to the previous failing call. The second test could be reported on $templatesFromConfig, and not $templates.

.. code-block:: php

    $templates = $input->getOption('template');
            if (!$templates) {
                /** @var Template[] $templatesFromConfig */
                $templatesFromConfig = $configurationHelper->getConfigValueFromPath('transformations/templates');
                foreach ($templatesFromConfig as $template) {
                    $templates[] = $template->getName();
                }
            }
    
            if (!$templates) {
                $templates = array('clean');
            }

Throw Functioncall
==================

.. _sugarcrm-exceptions-throwfunctioncall:

SugarCrm
^^^^^^^^

:ref:`throw-functioncall`, in include/externalAPI/cmis_repository_wrapper.php:918. 

SugarCRM uses exceptions to fill work in progress. Here, we recognize a forgotten 'new' that makes throw call a function named 'Exception'. This fails with a Fatal Error, and doesn't issue the right messsage. The same error had propgated in the code by copy and paste : it is available 17 times in that same file.

.. code-block:: php

    function getContentChanges()
        {
            throw Exception("Not Implemented");
        }

Use Instanceof
==============

.. _teampass-classes-useinstanceof:

TeamPass
^^^^^^^^

:ref:`use-instanceof`, in includes/libraries/Database/Meekrodb/db.class.php:506. 

In this code, ``is_object()`` and ``instanceof`` have the same basic : they both check that $ts is an object. In fact, ``instanceof`` is more precise, and give more information about the variable. 

.. code-block:: php

    protected function parseTS($ts) {
        if (is_string($ts)) return date('Y-m-d H:i:s', strtotime($ts));
        else if (is_object($ts) && ($ts instanceof DateTime)) return $ts->format('Y-m-d H:i:s');
      }


--------


.. _zencart-classes-useinstanceof:

Zencart
^^^^^^^

:ref:`use-instanceof`, in includes/modules/payment/firstdata_hco.php:104. 

In this code, ``is_object()`` is used to check the status of the order. Possibly, $order is false or null in case of incompatible status. Yet, when $object is an object, and in particular being a global that may be assigned anywhere else in the code, it seems that the method 'update_status' is magically always available. Here, using instance of to make sure that $order is an 'paypal' class, or a 'storepickup' or any of the payment class.  

.. code-block:: php

    function __construct() {
        global $order;
    
        // more lines, no mention of $order
        if (is_object($order)) $this->update_status();
    
        // more code
    }

Multiple Alias Definitions
==========================

.. _churchcrm-namespaces-multiplealiasdefinitions:

ChurchCRM
^^^^^^^^^

:ref:`multiple-alias-definitions`, in Various files:**. 

It is actually surprising to find FamilyQuery defined as ChurchCRM\Base\FamilyQuery only once, while all other reference are for ChurchCRM\FamilyQuery. That lone use is actually useful in the code, so it is not a forgotten refactorisation. 

.. code-block:: php

    use ChurchCRM\Base\FamilyQuery	// in /src/MapUsingGoogle.php:7
    
    use ChurchCRM\FamilyQuery	// in /src/ChurchCRM/Dashboard/EventsDashboardItem.php:8
                                // and 29 other files


--------


.. _phinx-namespaces-multiplealiasdefinitions:

Phinx
^^^^^

:ref:`multiple-alias-definitions`, in Various files:**. 

One 'Command' is refering to a local Command class, while the other is refering to an imported class. They are all in a similar name space Console\Command. 

.. code-block:: php

    use Phinx\Console\Command	                    //in file /src/Phinx/Console/PhinxApplication.php:34
    use Symfony\Component\Console\Command\Command	//in file /src/Phinx/Console/Command/Init.php:31
    use Symfony\Component\Console\Command\Command	//in file /src/Phinx/Console/Command/AbstractCommand.php:32

Cast To Boolean
===============

.. _mediawiki-structures-casttoboolean:

MediaWiki
^^^^^^^^^

:ref:`cast-to-boolean`, in includes/page/WikiPage.php:2274. 

$options['changed'] and $options['created'] are documented and used as boolean. Yet, SiteStatsUpdate may require integers, for correct storage in the database, hence the type casting. ``(int) (bool)`` may be an alternative here.

.. code-block:: php

    $edits = $options['changed'] ? 1 : 0;
    		$pages = $options['created'] ? 1 : 0;
    		
    
    		DeferredUpdates::addUpdate( SiteStatsUpdate::factory(
    			[ 'edits' => $edits, 'articles' => $good, 'pages' => $pages ]
    		) );


--------


.. _dolibarr-structures-casttoboolean:

Dolibarr
^^^^^^^^

:ref:`cast-to-boolean`, in htdocs/societe/class/societe.class.php:2777. 

Several cases are built on the same pattern there. Each of the expression may be replaced by a cast to ``(bool)``.

.. code-block:: php

    case 3:
    				$ret=(!$conf->global->SOCIETE_IDPROF3_UNIQUE?false:true);
    				break;

Failed Substr Comparison
========================

.. _zurmo-structures-failingsubstrcomparison:

Zurmo
^^^^^

:ref:`failed-substr-comparison`, in app/protected/modules/zurmo/modules/SecurableModule.php:117. 

filterAuditEvent compares a six char string with 'AUDIT\_EVENT\_' which contains 10 chars. This method returns only FALSE. Although it is used only once, the whole block that calls this method is now dead code. 

.. code-block:: php

    private static function filterAuditEvent($s)
            {
                return substr($s, 0, 6) == 'AUDIT_EVENT_';
            }


--------


.. _mediawiki-structures-failingsubstrcomparison:

MediaWiki
^^^^^^^^^

:ref:`failed-substr-comparison`, in includes/media/DjVu.php:263. 

$metadata contains data that may be in different formats. When it is a pure XML file, it is 'Old style'. The comment helps understanding that this is not the modern way to go : the Old Style is actually never called, due to a failing condition.

.. code-block:: php

    private function getUnserializedMetadata( File $file ) {
    		$metadata = $file->getMetadata();
    		if ( substr( $metadata, 0, 3 ) === '<?xml' ) {
    			// Old style. Not serialized but instead just a raw string of XML.
    			return $metadata;
    		}

Don't Echo Error
================

.. _churchcrm-security-dontechoerror:

ChurchCRM
^^^^^^^^^

:ref:`don't-echo-error`, in wp-admin/includes/misc.php:74. 

This is classic debugging code that should never reach production. mysqli_error() and mysqli_errno() provide valuable information is case of an error, and may be exploited by intruders.

.. code-block:: php

    if (mysqli_error($cnInfoCentral) != '') {
            echo gettext('An error occured: ').mysqli_errno($cnInfoCentral).'--'.mysqli_error($cnInfoCentral);
        } else {


--------


.. _phpdocumentor-security-dontechoerror:

Phpdocumentor
^^^^^^^^^^^^^

:ref:`don't-echo-error`, in src/phpDocumentor/Plugin/Graphs/Writer/Graph.php:77. 

Default development behavior : display the caught exception. Production behavior should not display that message, but log it for later review. Also, the return in the catch should be moved to the main code sequence.

.. code-block:: php

    public function processClass(ProjectDescriptor $project, Transformation $transformation)
        {
            try {
                $this->checkIfGraphVizIsInstalled();
            } catch (\Exception $e) {
                echo $e->getMessage();
    
                return;
            }

No isset() With empty()
=======================

.. _xoops-structures-noissetwithempty:

XOOPS
^^^^^

:ref:`no-isset()-with-empty()`, in /htdocs/class/tree.php:297. 

Too much vlaidation

.. code-block:: php

    isset($this->tree[$key]['child']) && !empty($this->tree[$key]['child']);

Bail Out Early
==============

.. _openemr-structures-bailoutearly:

OpenEMR
^^^^^^^

:ref:`bail-out-early`, in interface/modules/zend_modules/module/Carecoordination/src/Carecoordination/Controller/EncounterccdadispatchController.php:69. 

This is a typical example of a function mostly controlled by one condition. It could be rewrite as 'if($validResult != 'existingpatient')' then return. The 'else' clause is not used anymore, and the whole block of code is now the main sequence of the method. 

.. code-block:: php

    public function ccdaFetching($parameterArray = array())
        {
            $validResult = $this->getEncounterccdadispatchTable()->valid($parameterArray[0]);
            // validate credentials
            if ($validResult == 'existingpatient') {
    /// Long bloc of code
            } else {
                return '<?xml version=1.0 encoding=UTF-8?>
    			<!-- Edited by XMLSpy -->
    			<note>
    
    				<heading>Authetication Failure</heading>
    				<body></body>
    			</note>
    			';
            }


--------


.. _opencfp-structures-bailoutearly:

opencfp
^^^^^^^

:ref:`bail-out-early`, in chair/assign_auto_reviewers_weighted_topic_match.inc:105. 

This long example illustrates two aspects : first, the shortcut to the end of the method may be the 'then' clause, not necessarily the 'else'. '!in_array($pid.'-'.$rid, $conflictAR)' leads to return, and the 'else' should be removed, while keeping its content. Secondly, we can see 3 conditions that all lead to a premature end to the method. After refactoring all of them, the method would end up with 1 level of indentation, instead of 3.

.. code-block:: php

    function oc_inConflict(&$conflictAR, $pid, $rid=null) {
    	if ($rid == null) {
    		$rid = $_SESSION[OCC_SESSION_VAR_NAME]['acreviewerid'];
    	}
    	if (!in_array($pid.'-'.$rid, $conflictAR)) {
    		return false; // not in conflict
    	} else {
    		$tempr = ocsql_query("SELECT COUNT(*) AS `count` FROM `" . OCC_TABLE_PAPERREVIEWER . "` WHERE `paperid`='" . safeSQLstr($pid) . "' AND `reviewerid`='" . safeSQLstr($rid) . "'");
    		if ((ocsql_num_rows($tempr) == 1)
    			&& ($templ = ocsql_fetch_assoc($tempr))
    			&& ($templ['count'] == 1)
    		) {
    			return false; // assigned as reviewer
    		} else {
    			$tempr = ocsql_query("SELECT COUNT(*) AS `count` FROM `" . OCC_TABLE_PAPERADVOCATE . "` WHERE `paperid`='" . safeSQLstr($pid) . "' AND `advocateid`='" . safeSQLstr($rid) . "'");
    			if ((ocsql_num_rows($tempr) == 1)
    				&& ($templ = ocsql_fetch_assoc($tempr))
    				&& ($templ['count'] == 1)
    			) {
    				return false; // assigned as advocate
    			}
    		}
    	}
    	return true;
    }

Too Many Local Variables
========================

.. _humo-gen-functions-toomanylocalvariables:

HuMo-Gen
^^^^^^^^

:ref:`too-many-local-variables`, in relations.php:813. 

15 local variables pieces of code are hard to find in a compact form. This function shows one classic trait of such issue : a large ifthen is at the core of the function, and each time, it collects some values and build a larger string. This should probably be split between different methods in a class. 

.. code-block:: php

    function calculate_nephews($generX) { // handed generations x is removed from common ancestor
    global $db_functions, $reltext, $sexe, $sexe2, $language, $spantext, $selected_language, $foundX_nr, $rel_arrayX, $rel_arrayspouseX, $spouse;
    global $reltext_nor, $reltext_nor2; // for Norwegian and Danish
    
    	if($selected_language=="es"){
    		if($sexe=="m") { $neph=__('nephew'); $span_postfix="o "; $grson='nieto'; }
    		else { $neph=__('niece'); $span_postfix="a "; $grson='nieta'; }
    		//$gendiff = abs($generX - $generY); // FOUT
    		$gendiff = abs($generX - $generY) - 1;
    		$gennr=$gendiff-1;
    		$degree=$grson." ".$gennr.$span_postfix;
    		if($gendiff ==1) { $reltext=$neph.__(' of ');}
    		elseif($gendiff > 1 AND $gendiff < 27) {
    			spanish_degrees($gendiff,$grson);
    			$reltext=$neph." ".$spantext.__(' of ');
    		}
    		else { $reltext=$neph." ".$degree; }
    	} elseif ($selected_language==he){
    		if($sexe=='m') { $nephniece = __('nephew'); }
    ///............

Illegal Name For Method
=======================

.. _prestashop-classes-wrongname:

PrestaShop
^^^^^^^^^^

:ref:`illegal-name-for-method`, in /admin-dev/ajaxfilemanager/inc/class.pagination.php:200. 

__getBaseUrl and __setBaseUrl shouldn't be named like that. 

.. code-block:: php

    /**
    	 * get base url for pagination links aftr excluded those key
    	 * identified on excluded query strings
    	 *
    	 */
    	function __getBaseUrl()
    	{
    
    		if(empty($this->baseUrl))
    		{
    
    			$this->__setBaseUrl();
    		}
    		return $this->baseUrl;
    	}

Suspicious Comparison
=====================

.. _phpipam-structures-suspiciouscomparison:

PhpIPAM
^^^^^^^

:ref:`suspicious-comparison`, in app/tools/vrf/index.php:110. 

if $subnet['description'] is a string, the comparison with 0 turn it into a boolean. false's length is 0, and true length is 1. PHP saves the day.

.. code-block:: php

    $subnet['description'] = strlen($subnet['description']==0) ? "/" : $subnet['description'];


--------


.. _expressionengine-structures-suspiciouscomparison:

ExpressionEngine
^^^^^^^^^^^^^^^^

:ref:`suspicious-comparison`, in ExpressionEngine_Core2.9.2/system/expressionengine/libraries/simplepie/SimplePie/Misc.php:1925. 

If trim($attribs['']['mode']) === 'base64', then it is set to lowercase (although it is already), and added to the && logical test. If it is 'BASE64', this fails.

.. code-block:: php

    if (isset($attribs['']['mode']) && strtolower(trim($attribs['']['mode']) === 'base64'))

No Empty Regex
==============

.. _tikiwiki-structures-noemptyregex:

Tikiwiki
^^^^^^^^

:ref:`no-empty-regex`, in lib/sheet/excel/writer/worksheet.php:1925. 

The initial 's' seems to be too much. May be a typo ? 

.. code-block:: php

    // Strip URL type
            $url = preg_replace('s[^internal:]', '', $url);

Randomly Sorted Arrays
======================

.. _contao-arrays-randomlysortedliterals:

Contao
^^^^^^

:ref:`randomly-sorted-arrays`, in system/modules/core/dca/tl_module.php:259. 

The array array('maxlength', 'decodeEntities', 'tl_class') is configured multiple times in this file. Most of them is in the second form, but some are in the first form. (Multiple occurrences in this file). 

.. code-block:: php

    array('maxlength' => 255, 'decodeEntities' => true, 'tl_class' => 'w50') // Line 246
    array('decodeEntities' => true, 'maxlength' => 255, 'tl_class' => 'w50'); // ligne 378


--------


.. _vanilla-arrays-randomlysortedliterals:

Vanilla
^^^^^^^

:ref:`randomly-sorted-arrays`, in applications/dashboard/models/class.activitymodel.php:308. 

'Photo' moved from last to second. This array is used with a 'Join' key, and is the base for a SQL table JOIN. As such, order is important. If this is the case, it seems unusual that the order is not the same for a join using the same tables. If it is not the case, arrays may be reordered. 

.. code-block:: php

    /* L 305 */        Gdn::userModel()->joinUsers(
                $result->resultArray(),
                ['ActivityUserID', 'RegardingUserID'],
                ['Join' => ['Name', 'Email', 'Gender', 'Photo']]
            );
    
    // L 385
            Gdn::userModel()->joinUsers($result, ['ActivityUserID', 'RegardingUserID'], ['Join' => ['Name', 'Photo', 'Email', 'Gender']]);

Only Variable Passed By Reference
=================================

.. _dolphin-functions-onlyvariablepassedbyreference:

Dolphin
^^^^^^^

:ref:`only-variable-passed-by-reference`, in administration/charts.json.php:89. 

This is not possible, as array_slice returns a new array, and not a reference. Minimaly, the intermediate result must be saved in a variable, to be popped. Actually, this code extracts the element at key 1 in the $aData array, although this also works with hash (non-numeric keys).

.. code-block:: php

    array_pop(array_slice($aData, 0, 1))


--------


.. _phpipam-functions-onlyvariablepassedbyreference:

PhpIPAM
^^^^^^^

:ref:`only-variable-passed-by-reference`, in functions/classes/class.Thread.php:243. 

This is sneaky bug : the assignation $status = 0 returns a value, and not a variable. This leads PHP to mistake the initialized 0 with the variable $status and faild. It is not possible to initialize variable AND use them as argument.

.. code-block:: php

    pcntl_waitpid($this->pid, $status = 0)

Mixed Concat And Interpolation
==============================

.. _suitecrm-structures-mixedconcatinterpolation:

SuiteCRM
^^^^^^^^

:ref:`mixed-concat-and-interpolation`, in modules/AOW_Actions/actions/actionSendEmail.php:89. 

How long did it take to spot the hidden $checked variable in this long concatenation ? Using a consistent method of interpolation would help readability here.

.. code-block:: php

    "<input type='checkbox' id='aow_actions_param[" . $line . "][individual_email]' name='aow_actions_param[" . $line . "][individual_email]' value='1' $checked></td>"


--------


.. _edusoho-structures-mixedconcatinterpolation:

Edusoho
^^^^^^^

:ref:`mixed-concat-and-interpolation`, in src/AppBundle/Controller/Admin/SiteSettingController.php:168. 

Calling a method from a property of an object is possible inside a string, though it is rare. Setting the method outside the string make it more readable.

.. code-block:: php

    "{$this->container->getParameter('topxia.upload.public_url_path')}/" . $parsed['path']

Too Many Injections
===================

.. _nextcloud-classes-toomanyinjections:

NextCloud
^^^^^^^^^

:ref:`too-many-injections`, in lib/private/Share20/Manager.php:130. 

Well documented Manager class. Quite a lot of injections though, it must take a long time to prepare it.

.. code-block:: php

    /**
    	 * Manager constructor.
    	 *
    	 * @param ILogger $logger
    	 * @param IConfig $config
    	 * @param ISecureRandom $secureRandom
    	 * @param IHasher $hasher
    	 * @param IMountManager $mountManager
    	 * @param IGroupManager $groupManager
    	 * @param IL10N $l
    	 * @param IFactory $l10nFactory
    	 * @param IProviderFactory $factory
    	 * @param IUserManager $userManager
    	 * @param IRootFolder $rootFolder
    	 * @param EventDispatcher $eventDispatcher
    	 * @param IMailer $mailer
    	 * @param IURLGenerator $urlGenerator
    	 * @param \OC_Defaults $defaults
    	 */
    	public function __construct(
    			ILogger $logger,
    			IConfig $config,
    			ISecureRandom $secureRandom,
    			IHasher $hasher,
    			IMountManager $mountManager,
    			IGroupManager $groupManager,
    			IL10N $l,
    			IFactory $l10nFactory,
    			IProviderFactory $factory,
    			IUserManager $userManager,
    			IRootFolder $rootFolder,
    			EventDispatcher $eventDispatcher,
    			IMailer $mailer,
    			IURLGenerator $urlGenerator,
    			\OC_Defaults $defaults
    	) {
    		$this->logger = $logger;
    		$this->config = $config;
    		$this->secureRandom = $secureRandom;
    		$this->hasher = $hasher;
    		$this->mountManager = $mountManager;
    		$this->groupManager = $groupManager;
    		$this->l = $l;
    		$this->l10nFactory = $l10nFactory;
    		$this->factory = $factory;
    		$this->userManager = $userManager;
    		$this->rootFolder = $rootFolder;
    		$this->eventDispatcher = $eventDispatcher;
    		$this->sharingDisabledForUsersCache = new CappedMemoryCache();
    		$this->legacyHooks = new LegacyHooks($this->eventDispatcher);
    		$this->mailer = $mailer;
    		$this->urlGenerator = $urlGenerator;
    		$this->defaults = $defaults;
    	}


--------


.. _thelia-classes-toomanyinjections:

Thelia
^^^^^^

:ref:`too-many-injections`, in lib/private/Share20/Manager.php:130. 

Classic address class, with every details. May be even shorter than expected.

.. code-block:: php

    //class DeliveryPostageEvent extends ActionEvent
        public function __construct(
            DeliveryModuleInterface $module,
            Cart $cart,
            Address $address = null,
            Country $country = null,
            State $state = null
        ) {
            $this->module = $module;
            $this->cart = $cart;
            $this->address = $address;
            $this->country = $country;
            $this->state = $state;
        }

Assign With And
===============

.. _xataface-php-assignand:

xataface
^^^^^^^^

:ref:`assign-with-and`, in Dataface/LanguageTool.php:265. 

The usage of 'and' here is a workaround for PHP version that have no support for the coalesce. $autosubmit receives the value of $params['autosubmit'] only if the latter is set. Yet, with = having higher precedence over 'and', $autosubmit is mistaken with the existence of $params['autosubmit'] : its value is actually omitted.

.. code-block:: php

    $autosubmit = isset($params['autosubmit']) and $params['autosubmit'];

Logical To in_array
===================

.. _zencart-performances-logicaltoinarray:

Zencart
^^^^^^^

:ref:`logical-to-in\_array`, in admin/users.php:32. 

Long list of == are harder to read. Using an in_array() call gathers all the strings together, in an array. In turn, this helps readability and possibility, reusability by making that list an constant. 

.. code-block:: php

    // if needed, check that a valid user id has been passed
    if (($action == 'update' || $action == 'reset') && isset($_POST['user']))
    {
      $user = $_POST['user'];
    }
    elseif (($action == 'edit' || $action == 'password' || $action == 'delete' || $action == 'delete_confirm') && $_GET['user'])
    {
      $user = $_GET['user'];
    }
    elseif(($action=='delete' || $action=='delete_confirm') && isset($_POST['user']))
    {
      $user = $_POST['user'];
    }

Is Actually Zero
================

.. _dolibarr-structures-iszero:

Dolibarr
^^^^^^^^

:ref:`is-actually-zero`, in htdocs/compta/ajaxpayment.php:99. 

Here, the $amountToBreakDown is either $currentRemain or $result. 

.. code-block:: php

    $amountToBreakdown = ($result - $currentRemain >= 0 ?
    										$currentRemain : 								// Remain can be fully paid
    										$currentRemain + ($result - $currentRemain));	// Remain can only partially be paid


--------


.. _suitecrm-structures-iszero:

SuiteCRM
^^^^^^^^

:ref:`is-actually-zero`, in modules/AOR_Charts/lib/pChart/class/pDraw.class.php:523. 

$Xa may only amount to $iX2, though the expression looks weird.

.. code-block:: php

    if ( $X > $iX2 ) { $Xa = $X-($X-$iX2); $Ya = $iY1+($X-$iX2); } else { $Xa = $X; $Ya = $iY1; }

Next Month Trap
===============

.. _contao-structures-nextmonthtrap:

Contao
^^^^^^

:ref:`next-month-trap`, in system/modules/calendar/classes/Events.php:515. 

This code is wrong on August 29,th 30th and 31rst : 6 months before is caculated here as February 31rst, so march 2. Of course, this depends on the leap years.

.. code-block:: php

    case 'past_180':
    				return array(strtotime('-6 months'), time(), $GLOBALS['TL_LANG']['MSC']['cal_empty']);


--------


.. _edusoho-structures-nextmonthtrap:

Edusoho
^^^^^^^

:ref:`next-month-trap`, in src/AppBundle/Controller/Admin/AnalysisController.php:1426. 

The last month is wrong 8 times a year : on 31rst, and by the end of March. 

.. code-block:: php

    'lastMonthStart' => date('Y-m-d', strtotime(date('Y-m', strtotime('-1 month')))),
                'lastMonthEnd' => date('Y-m-d', strtotime(date('Y-m', time())) - 24 * 3600),
                'lastThreeMonthsStart' => date('Y-m-d', strtotime(date('Y-m', strtotime('-2 month')))),

Don't Send $this In Constructor
===============================

.. _woocommerce-classes-dontsendthisinconstructor:

Woocommerce
^^^^^^^^^^^

:ref:`don't-send-$this-in-constructor`, in includes/class-wc-cart.php:107. 

WC_Cart_Session and WC_Cart_Fees receives $this, the current object, at a moment where it is not consistent : for example, tax_display_cart hasn't been set yet. Although it may be unexpected to have an object called WC_Cart being called by the session or the fees, this is still a temporary inconsistence. 

.. code-block:: php

    /**
    	 * Constructor for the cart class. Loads options and hooks in the init method.
    	 */
    	public function __construct() {
    		$this->session          = new WC_Cart_Session( $this );
    		$this->fees_api         = new WC_Cart_Fees( $this );
    		$this->tax_display_cart = $this->is_tax_displayed();
    
    		// Register hooks for the objects.
    		$this->session->init();


--------


.. _contao-classes-dontsendthisinconstructor:

Contao
^^^^^^

:ref:`don't-send-$this-in-constructor`, in system/modules/core/library/Contao/Model.php:110. 

$this is send to $objRegistry. $objRegistry is obtained with a factory, \Model\Registry::getInstance(). It is probably fully prepared at that point. Yet, $objRegistry is called and used to fill $this properties with full values. At some point, $objRegistry return values without having a handle on a fully designed object. 

.. code-block:: php

    /**
    	 * Load the relations and optionally process a result set
    	 *
    	 * @param \Database\Result $objResult An optional database result
    	 */
    	public function __construct(\Database\Result $objResult=null)
    	{
            // Some code was removed 
    			$objRegistry = \Model\Registry::getInstance();
    
    			$this->setRow($arrData); // see #5439
    			$objRegistry->register($this);
    			
            // More code below
            // $this-> are set
            // $objRegistry is called 
        }

Parent First
============

.. _prestashop-classes-parentfirst:

PrestaShop
^^^^^^^^^^

:ref:`parent-first`, in wp-admin/includes/misc.php:74. 

A good number of properties are set in the current object even before the parent AdminController(Core) is called. 'table' and 'lang' acts as default values for the parent class, as it (the parent class) would set them to another default value. Many properties are used, but not defined in the current class, nor its parent. This approach prevents the constructor from requesting too many arguments. Yet, as such, it is difficult to follow which of the initial values are transmitted via protected/public properties rather than using the __construct() call.

.. code-block:: php

    class AdminWebserviceControllerCore extends AdminController
    {
        /** this will be filled later */
        public $fields_form = array('webservice form');
        protected $toolbar_scroll = false;
    
        public function __construct()
        {
            $this->bootstrap = true;
            $this->table = 'webservice_account';
            $this->className = 'WebserviceKey';
            $this->lang = false;
            $this->edit = true;
            $this->delete = true;
            $this->id_lang_default = Configuration::get('PS_LANG_DEFAULT');
    
            parent::__construct();

Identical On Both Sides
=======================

.. _phpmyadmin-structures-identicalonbothsides:

phpMyAdmin
^^^^^^^^^^

:ref:`identical-on-both-sides`, in libraries/classes/DatabaseInterface.php:323. 

This code looks like ``($options & DatabaseInterface::QUERY_STORE) == DatabaseInterface::QUERY_STORE``, which would make sense. But PHP precedence is actually executing ``$options & (DatabaseInterface::QUERY_STORE == DatabaseInterface::QUERY_STORE)``, which then doesn't depends on QUERY_STORE but only on $options.

.. code-block:: php

    if ($options & DatabaseInterface::QUERY_STORE == DatabaseInterface::QUERY_STORE) {
        $tmp = $this->_extension->realQuery('
            SHOW COUNT(*) WARNINGS', $this->_links[$link], DatabaseInterface::QUERY_STORE
        );
        $warnings = $this->fetchRow($tmp);
    } else {
        $warnings = 0;
    }

No Reference For Ternary
========================

.. _phpadsnew-php-noreferenceforternary:

phpadsnew
^^^^^^^^^

:ref:`no-reference-for-ternary`, in /lib/OA/Admin/Menu/Section.php334:334. 

The reference should be removed from the function definition. Either this method returns null, which is never a reference, or it returns $this, which is always a reference, or the results of a methodcall. The latter may or may not be a reference, but the Ternary operator will drop it and return by value. 

.. code-block:: php

    function &getParentOrSelf($type)
    	{
            if ($this->type == $type) {
                return $this;
            }
            else {
                return $this->parentSection != null ? $this->parentSection->getParentOrSelf($type) : null;
            }
    	}

Useless Referenced Argument
===========================

.. _woocommerce-functions-uselessreferenceargument:

Woocommerce
^^^^^^^^^^^

:ref:`useless-referenced-argument`, in includes/data-stores/class-wc-product-variation-data-store-cpt.php:414. 

$product is defined with a reference in the method signature, but it is also used as an object with a dynamical property. As such, the reference in the argument definition is too much.

.. code-block:: php

    public function update_post_meta( &$product, $force = false ) {
    		$meta_key_to_props = array(
    			'_variation_description' => 'description',
    		);
    
    		$props_to_update = $force ? $meta_key_to_props : $this->get_props_to_update( $product, $meta_key_to_props );
    
    		foreach ( $props_to_update as $meta_key => $prop ) {
    					$value   = $product->{get_$prop}( 'edit' );
    					$updated = update_post_meta( $product->get_id(), $meta_key, $value );
    			if ( $updated ) {
    				$this->updated_props[] = $prop;
    			}
    		}
    
    		parent::update_post_meta( $product, $force );


--------


.. _magento-functions-uselessreferenceargument:

Magento
^^^^^^^

:ref:`useless-referenced-argument`, in setup/src/Magento/Setup/Module/Di/Compiler/Config/Chain/PreferencesResolving.php:63. 

$value is defined with a reference. In the following code, it is only read and never written : for index search, or by itself. In fact, $preferences is also only read, and never written. As such, both could be removed.

.. code-block:: php

    private function resolvePreferenceRecursive(&$value, &$preferences)
        {
            return isset($preferences[$value])
                ? $this->resolvePreferenceRecursive($preferences[$value], $preferences)
                : $value;
        }

Test Then Cast
==============

.. _dolphin-structures-testthencast:

Dolphin
^^^^^^^

:ref:`test-then-cast`, in wp-admin/includes/misc.php:74. 

$aLimits['per_page'] is tested for existence and not false. Later, it is cast from string to int : yet, a '0.1' string value would pass the test, and end up filling $aLimits['per_page'] with 0. 

.. code-block:: php

    if (isset($aLimits['per_page']) && $aLimits['per_page'] !== false)
                $this->aCurrent['paginate']['perPage'] = (int)$aLimits['per_page'];


--------


.. _suitecrm-structures-testthencast:

SuiteCrm
^^^^^^^^

:ref:`test-then-cast`, in modules/jjwg_Maps/controller.php:1035. 

$marker['lat'] is compared to the string '0', which actually transtype it to integer, then it is cast to string for map_marker_data_points() needs and finally, it is cast to float, in case of a correction. It would be safer to test it in its string type, since floats are not used as array indices. 

.. code-block:: php

    if ($marker['lat'] != '0' && $marker['lng'] != '0') {
    
                // Check to see if marker point already exists and apply offset if needed
                // This often occurs when an address is only defined by city, state, zip.
                $i = 0;
                while (isset($this->map_marker_data_points[(string) $marker['lat']][(string) $marker['lng']]) &&
                $i < $this->settings['map_markers_limit']) {
                    $marker['lat'] = (float) $marker['lat'] + (float) $this->settings['map_duplicate_marker_adjustment'];
                    $marker['lng'] = (float) $marker['lng'] + (float) $this->settings['map_duplicate_marker_adjustment'];
                    $i++;
                }

Redefined Private Property
==========================

.. _zurmo-classes-redefinedprivateproperty:

Zurmo
^^^^^

:ref:`redefined-private-property`, in app/protected/modules/zurmo/models/OwnedCustomField.php:51. 

The class OwnedCustomField is part of a large class tree : OwnedCustomField extends CustomField,
CustomField extends BaseCustomField, BaseCustomField extends RedBeanModel, RedBeanModel extends BeanModel. 

Since $canHaveBean is distinct in BeanModel and in OwnedCustomField, the public method getCanHaveBean() also had to be overloaded. 

.. code-block:: php

    class OwnedCustomField extends CustomField
        {
            /**
             * OwnedCustomField does not need to have a bean because it stores no attributes and has no relations
             * @see RedBeanModel::canHaveBean();
             * @var boolean
             */
            private static $canHaveBean = false;
    
    /..../
    
            /**
             * @see RedBeanModel::getHasBean()
             */
            public static function getCanHaveBean()
            {
                if (get_called_class() == 'OwnedCustomField')
                {
                    return self::$canHaveBean;
                }
                return parent::getCanHaveBean();
            }

Don't Unset Properties
======================

.. _vanilla-classes-dontunsetproperties:

Vanilla
^^^^^^^

:ref:`don't-unset-properties`, in applications/dashboard/models/class.activitymodel.php:1073. 

The _NotificationQueue property, in this class, is defined as an array. Here, it is destroyed, then recreated. The unset() is too much, as the assignation is sufficient to reset the array 

.. code-block:: php

    /**
         * Clear notification queue.
         *
         * @since 2.0.17
         * @access public
         */
        public function clearNotificationQueue() {
            unset($this->_NotificationQueue);
            $this->_NotificationQueue = [];
        }


--------


.. _typo3-classes-dontunsetproperties:

Typo3
^^^^^

:ref:`don't-unset-properties`, in typo3/sysext/linkvalidator/Classes/Linktype/InternalLinktype.php:73. 

The property errorParams is emptied by unsetting it. The property is actually defined in the above class, as an array. Until the next error is added to this list, any access to the error list has to be checked with isset(), or yield an 'Undefined' warning. 

.. code-block:: php

    public function checkLink($url, $softRefEntry, $reference)
        {
            $anchor = '';
            $this->responseContent = true;
            // Might already contain values - empty it
            unset($this->errorParams);
    //....
    
    abstract class AbstractLinktype implements LinktypeInterface
    {
        /**
         * Contains parameters needed for the rendering of the error message
         *
         * @var array
         */
        protected $errorParams = [];

Strtr Arguments
===============

.. _suitecrm-php-strtrarguments:

SuiteCrm
^^^^^^^^

:ref:`strtr-arguments`, in includes/vCard.php:221. 

This code prepares incoming '$values' for extraction. The keys are cleaned then split with explode(). The '=' sign would stay, as strtr() can't remove it. This means that such keys won't be recognized later in the code, and gets omitted.

.. code-block:: php

    $values = explode(';', $value);
                        $key = strtoupper($keyvalue[0]);
                        $key = strtr($key, '=', '');
                        $key = strtr($key, ',', ';');
                        $keys = explode(';', $key);

Weak Typing
===========

.. _teampass-classes-weaktype:

TeamPass
^^^^^^^^

:ref:`weak-typing`, in includes/libraries/Tree/NestedTree/NestedTree.php:100. 

The is_null() test detects a special situation, that requires usage of default values. The 'else' handles every other situations, including when the $node is an object, or anything else. $this->getNode() will gain from having typehints : it may be NULL, or the results of mysqli_fetch_object() : a stdClass object. The expected properties of nleft and nright are not certain to be available.

.. code-block:: php

    public function getDescendants($id = 0, $includeSelf = false, $childrenOnly = false, $unique_id_list = false)
        {
            global $link;
            $idField = $this->fields['id'];
    
            $node = $this->getNode($id);
            if (is_null($node)) {
                $nleft = 0;
                $nright = 0;
                $parent_id = 0;
                $personal_folder = 0;
            } else {
                $nleft = $node->nleft;
                $nright = $node->nright;
                $parent_id = $node->$idField;
                $personal_folder = $node->personal_folder;
            }

Bad Constants Names
===================

.. _prestashop-constants-badconstantnames:

PrestaShop
^^^^^^^^^^

:ref:`bad-constants-names`, in src/PrestaShopBundle/Install/Upgrade.php:214. 

INSTALL_PATH is a valid name for a constant. __PS_BASE_URI__ is not a valid name.

.. code-block:: php

    require_once(INSTALL_PATH . 'install_version.php');
                // needed for upgrade before 1.5
                if (!defined('__PS_BASE_URI__')) {
                    define('__PS_BASE_URI__', str_replace('//', '/', '/'.trim(preg_replace('#/(install(-dev)?/upgrade)$#', '/', str_replace('\', '/', dirname($_SERVER['REQUEST_URI']))), '/').'/'));
                }


--------


.. _zencart-constants-badconstantnames:

Zencart
^^^^^^^

:ref:`bad-constants-names`, in zc_install/ajaxTestDBConnection.php:10. 

A case where PHP needs help : if the PHP version is older than 5.3, then it is valid to compensate. Though, this __DIR__ has a fixed value, wherever it is used, while the official __DIR__ change from dir to dir. 

.. code-block:: php

    if (!defined('__DIR__')) define('__DIR__', dirname(__FILE__));

Incompatible Signature Methods
==============================

.. _suitecrm-classes-incompatiblesignature:

SuiteCrm
^^^^^^^^

:ref:`incompatible-signature-methods`, in /modules/Home/Dashlets/RSSDashlet/RSSDashlet.php:138. 

The class in the RSSDashlet.php file has an 'array' typehint which is not in the parent Dashlet class. While both files compile separately, they yield a PHP warning when running : typehinting mismatch only yields a warning. 

.. code-block:: php

    // File /modules/Home/Dashlets/RSSDashlet/RSSDashlet.php
        public function saveOptions(
            array $req
            )
        {
    
    // File /include/Dashlets/Dashlets.php
        public function saveOptions( $req ) {

Could Be Abstract Class
=======================

.. _edusoho-classes-couldbeabstractclass:

Edusoho
^^^^^^^

:ref:`could-be-abstract-class`, in src/Biz/Task/Strategy/BaseStrategy.php:14. 

BaseStrategy is extended by NormalStrategy, DefaultStrategy (Not shown here), but it is not instantiated itself.

.. code-block:: php

    class BaseStrategy { 
        // Class code
    }


--------


.. _shopware-classes-couldbeabstractclass:

shopware
^^^^^^^^

:ref:`could-be-abstract-class`, in engine/Shopware/Plugins/Default/Core/PaymentMethods/Components/GenericPaymentMethod.php:31. 

A 'Generic' class sounds like a class that could be 'abstract'. 

.. code-block:: php

    class GenericPaymentMethod extends BasePaymentMethod { 
        // More class code
    }

Could Be Private Class Constant
===============================

.. _phinx-classes-couldbeprivateconstante:

Phinx
^^^^^

:ref:`could-be-private-class-constant`, in src/Phinx/Db/Adapter/MysqlAdapter.php:46. 

The code includes a fair number of class constants. The one listed here are only used to define TEXT columns in MySQL, with their maximal size. Since they are only intented to be used by the MySQL driver, they may be private.

.. code-block:: php

    class MysqlAdapter extends PdoAdapter implements AdapterInterface
    {
    
    //.....
        const TEXT_SMALL   = 255;
        const TEXT_REGULAR = 65535;
        const TEXT_MEDIUM  = 16777215;
        const TEXT_LONG    = 4294967295;

__debugInfo() Usage
===================

.. _dolibarr-php-debuginfousage:

Dolibarr
^^^^^^^^

:ref:`\_\_debuginfo()-usage`, in htdocs/includes/stripe/lib/StripeObject.php:108. 

_values is a private property from the Stripe Class. The class contains other objects, but only _values are displayed with var_dump.

.. code-block:: php

    // Magic method for var_dump output. Only works with PHP >= 5.6
        public function __debugInfo()
        {
            return $this->_values;
        }

PHP7 Dirname
============

.. _openconf-structures-php7dirname:

OpenConf
^^^^^^^^

:ref:`php7-dirname`, in include.php:61. 

Since PHP 7.0, dirname( , 2); does the job.

.. code-block:: php

    $OC_basepath = dirname(dirname($_SERVER['PHP_SELF']));


--------


.. _mediawiki-structures-php7dirname:

MediaWiki
^^^^^^^^^

:ref:`php7-dirname`, in includes/installer/Installer.php:1173. 

Since PHP 7.0, dirname( , 2); does the job.

.. code-block:: php

    protected function envPrepPath() {
    		global $IP;
    		$IP = dirname( dirname( __DIR__ ) );
    		$this->setVar( 'IP', $IP );
    	}

Unused Private Properties
=========================

.. _openemr-classes-unusedprivateproperty:

OpenEMR
^^^^^^^

:ref:`unused-private-properties`, in entities/User.php:46. 

This class has a long list of private properties. It also has an equally long (minus one) list of accessors, and a __toString() method which exposes all of them. $oNotes is the only one never mentionned anywhere. 

.. code-block:: php

    class User
    {
        /**
         * @Column(name=id, type=integer)
         * @GeneratedValue(strategy=AUTO)
         */
        private $id;
    
        /**
         * @OneToMany(targetEntity=ONote, mappedBy=user)
         */
        private $oNotes;


--------


.. _phpadsnew-classes-unusedprivateproperty:

phpadsnew
^^^^^^^^^

:ref:`unused-private-properties`, in lib/OA/Admin/UI/component/Form.php:23. 

$dispatcher is never used anywhere. 

.. code-block:: php

    class OA_Admin_UI_Component_Form
        extends HTML_QuickForm
    {
        private $dispatcher;

Exception Order
===============

.. _woocommerce-exceptions-alreadycaught:

Woocommerce
^^^^^^^^^^^

:ref:`exception-order`, in includes/api/v1/class-wc-rest-products-controller.php:787. 

This try/catch expression is able to catch both WC_Data_Exception and WC_REST_Exception. 

In another file, /includes/api/class-wc-rest-exception.php, we find that WC_REST_Exception extends WC_Data_Exception (class WC_REST_Exception extends WC_Data_Exception {}). So WC_Data_Exception is more general, and a WC_REST_Exception exception is caught with WC_Data_Exception Exception. The second catch should be put in first.

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    try {
    			$product_id = $this->save_product( $request );
    			$post       = get_post( $product_id );
    			$this->update_additional_fields_for_object( $post, $request );
    			$this->update_post_meta_fields( $post, $request );
    
    			/**
    			 * Fires after a single item is created or updated via the REST API.
    			 *
    			 * @param WP_Post         $post      Post data.
    			 * @param WP_REST_Request $request   Request object.
    			 * @param boolean         $creating  True when creating item, false when updating.
    			 */
    			do_action( 'woocommerce_rest_insert_product', $post, $request, false );
    			$request->set_param( 'context', 'edit' );
    			$response = $this->prepare_item_for_response( $post, $request );
    
    			return rest_ensure_response( $response );
    		} catch ( WC_Data_Exception $e ) {
    			return new WP_Error( $e->getErrorCode(), $e->getMessage(), $e->getErrorData() );
    		} catch ( WC_REST_Exception $e ) {
    			return new WP_Error( $e->getErrorCode(), $e->getMessage(), array( 'status' => $e->getCode() ) );
    		}

Rethrown Exceptions
===================

.. _prestashop-exceptions-rethrown:

PrestaShop
^^^^^^^^^^

:ref:`rethrown-exceptions`, in classes/webservice/WebserviceOutputBuilder.php:731. 

The setSpecificField method catches a WebserviceException, representing an issue with the call to the webservice. However, that piece of information is lost, and the exception is rethrown immediately, without any action.

.. code-block:: php

    public function setSpecificField($object, $method, $field_name, $entity_name)
    	{
    		try {
    			$this->validateObjectAndMethod($object, $method);
    		} catch (WebserviceException $e) {
    			throw $e;
    		}
    
    		$this->specificFields[$field_name] = array('entity'=>$entity_name, 'object' => $object, 'method' => $method, 'type' => gettype($object));
    		return $this;
    	}

Slow Functions
==============

.. _suitecrm-performances-slowfunctions:

SuiteCRM
^^^^^^^^

:ref:`slow-functions`, in include/json_config.php:242. 

This is a equivalent for nl2br()

.. code-block:: php

    preg_replace("/\r\n/", "<BR>", $focus->$field)

Join file()
===========

.. _wordpress-performances-joinfile:

WordPress
^^^^^^^^^

:ref:`join-file()`, in wp-admin/includes/misc.php:74. 

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    $markerdata = explode( "\n", implode( '', file( $filename ) ) );


--------


.. _spip-performances-joinfile:

SPIP
^^^^

:ref:`join-file()`, in ecrire/inc/install.php:109. 

When the file is not accessible, file() returns null, and can't be processed by join(). 

.. code-block:: php

    $s = @join('', file($file));


--------


.. _expressionengine-performances-joinfile:

ExpressionEngine
^^^^^^^^^^^^^^^^

:ref:`join-file()`, in ExpressionEngine_Core2.9.2/system/expressionengine/libraries/simplepie/idn/idna_convert.class.php:100. 

join('', ) is used as a replacement for file_get_contents(), which was introduced in PHP 4.3.0.

.. code-block:: php

    if (function_exists('file_get_contents')) {
        $this->NP = unserialize(file_get_contents(dirname(__FILE__).'/npdata.ser'));
    } else {
        $this->NP = unserialize(join('', file(dirname(__FILE__).'/npdata.ser')));
    }


--------


.. _prestashop-performances-joinfile:

PrestaShop
^^^^^^^^^^

:ref:`join-file()`, in classes/module/Module.php:2972. 

implode('', ) is probably not the slowest part in these lines.

.. code-block:: php

    $override_file = file($override_path);
    
    eval(preg_replace(array('#^\s*<\?(?:php)?#', '#class\s+'.$classname.'\s+extends\s+([a-z0-9_]+)(\s+implements\s+([a-z0-9_]+))?#i'), array(' ', 'class '.$classname.'OverrideOriginal_remove'.$uniq), implode('', $override_file)));
    $override_class = new ReflectionClass($classname.'OverrideOriginal_remove'.$uniq);
    
    $module_file = file($this->getLocalPath().'override/'.$path);
    eval(preg_replace(array('#^\s*<\?(?:php)?#', '#class\s+'.$classname.'(\s+extends\s+([a-z0-9_]+)(\s+implements\s+([a-z0-9_]+))?)?#i'), array(' ', 'class '.$classname.'Override_remove'.$uniq), implode('', $module_file)));

Make One Call With Array
========================

.. _humo-gen-performances-makeonecall:

HuMo-Gen
^^^^^^^^

:ref:`make-one-call-with-array`, in admin/include/kcfinder/lib/helper_text.php:47. 

The three calls to str_replace() could be replaced by one, using array arguments. Nesting the calls doesn't reduce the number of calls.

.. code-block:: php

    static function jsValue($string) {
            return
                preg_replace('/\r?\n/', "\n",
                str_replace('"', "\\"",
                str_replace("'", "\'",
                str_replace("\", "\\",
            $string))));
        }


--------


.. _edusoho-performances-makeonecall:

Edusoho
^^^^^^^

:ref:`make-one-call-with-array`, in src/AppBundle/Common/StringToolkit.php:55. 

Since str_replace is already using an array, the second argument must also be an array, with repeated empty strings. That syntax allows adding the '&nbsp;' and ' ' to those arrays. Note also that trim() should be be called early, but since some of the replacing may generate terminal spaces, it should be kept as is.

.. code-block:: php

    $text = strip_tags($text);
    
            $text = str_replace(array(\n, \r, \t), '', $text);
            $text = str_replace('&nbsp;', ' ', $text);
            $text = trim($text);

No Count With 0
===============

.. _contao-performances-notcountnull:

Contao
^^^^^^

:ref:`no-count-with-0`, in system/modules/repository/classes/RepositoryManager.php:1148. 

If $elist contains at least one element, then it is not empty().

.. code-block:: php

    $ext->found = count($elist)>0;


--------


.. _wordpress-performances-notcountnull:

WordPress
^^^^^^^^^

:ref:`no-count-with-0`, in wp-admin/includes/misc.php:74. 

$build or $signature are empty at that point, no need to calculate their respective length. 

.. code-block:: php

    // Check for zero length, although unlikely here
        if (strlen($built) == 0 || strlen($signature) == 0) {
          return false;
        }

time() Vs strtotime()
=====================

.. _woocommerce-performances-timevsstrtotime:

Woocommerce
^^^^^^^^^^^

:ref:`time()-vs-strtotime()`, in includes/class-wc-webhook.php:384. 

time() would be faster here, as an entropy generator. Yet, it would still be better to use an actual secure entropy generator, like random_byte or random_int. In case of older version, microtime() would yield better entropy. 

.. code-block:: php

    public function get_new_delivery_id() {
    		// Since we no longer use comments to store delivery logs, we generate a unique hash instead based on current time and webhook ID.
    		return wp_hash( $this->get_id() . strtotime( 'now' ) );
    	}

Avoid glob() Usage
==================

.. _phinx-performances-noglob:

Phinx
^^^^^

:ref:`avoid-glob()-usage`, in src/Phinx/Migration/Manager.php:362. 

glob() searches for a list of files in the migration folder. Those files are not known, but they have a format, as checked later with the regex : a combinaison of FilesystemIterator and RegexIterator would do the trick too.

.. code-block:: php

    $phpFiles = glob($config->getMigrationPath() . DIRECTORY_SEPARATOR . '*.php');
    
                // filter the files to only get the ones that match our naming scheme
                $fileNames = array();
                /** @var AbstractMigration[] $versions */
                $versions = array();
    
                foreach ($phpFiles as $filePath) {
                    if (preg_match('/([0-9]+)_([_a-z0-9]*).php/', basename($filePath))) {


--------


.. _nextcloud-performances-noglob:

NextCloud
^^^^^^^^^

:ref:`avoid-glob()-usage`, in lib/private/legacy/helper.php:185. 

Recursive copy of folders, based on scandir. DirectoryIterator and FilesystemIterator would do the same without the recursion.

.. code-block:: php

    static function copyr($src, $dest) {
    		if (is_dir($src)) {
    			if (!is_dir($dest)) {
    				mkdir($dest);
    			}
    			$files = scandir($src);
    			foreach ($files as $file) {
    				if ($file != "." && $file != "..") {
    					self::copyr("$src/$file", "$dest/$file");
    				}
    			}
    		} elseif (file_exists($src) && !\OC\Files\Filesystem::isFileBlacklisted($src)) {
    			copy($src, $dest);
    		}
    	}

Avoid Concat In Loop
====================

.. _suitecrm-performances-noconcatinloop:

SuiteCrm
^^^^^^^^

:ref:`avoid-concat-in-loop`, in include/export_utils.php:433. 

$line is build in several steps, then then final version is added to $content. It would be much faster to make $content an array, and implode it once after the loop. 

.. code-block:: php

    foreach($records as $record)
            {
                $line = implode("\"" . getDelimiter() . "\"", $record);
                $line = "\"" . $line;
                $line .= "\"\r\n";
                $line = parseRelateFields($line, $record, $customRelateFields);
                $content .= $line;
            }


--------


.. _thinkphp-performances-noconcatinloop:

ThinkPHP
^^^^^^^^

:ref:`avoid-concat-in-loop`, in include/export_utils.php:433. 

The  foreach loop prepares the 'getControllerRoute' call, then, accumulates all the resulting strings in $content. It would be much faster to make $content an array, and implode it once after the loop. 

.. code-block:: php

    foreach ($controllers as $controller) {
                $controller = basename($controller, '.php');
    
                $class = new \ReflectionClass($namespace . '\' . $module . '\' . $layer . '\' . $controller);
    
                if (strpos($layer, '\')) {
                    // 多级控制器
                    $level      = str_replace(DIRECTORY_SEPARATOR, '.', substr($layer, 11));
                    $controller = $level . '.' . $controller;
                    $length     = strlen(strstr($layer, '\', true));
                } else {
                    $length = strlen($layer);
                }
    
                if ($suffix) {
                    $controller = substr($controller, 0, -$length);
                }
    
                $content .= $this->getControllerRoute($class, $module, $controller);
            }

Use pathinfo() Arguments
========================

.. _zend-config-php-usepathinfoargs:

Zend-Config
^^^^^^^^^^^

:ref:`use-pathinfo()-arguments`, in src/Factory.php:74:90. 

The `$filepath` is broken into pieces, and then, only the 'extension' part is used. With the PATHINFO_EXTENSION constant used as a second argument, only this value could be returned. 

.. code-block:: php

    $pathinfo = pathinfo($filepath);
    
            if (! isset($pathinfo['extension'])) {
                throw new Exception\RuntimeException(sprintf(
                    'Filename "%s" is missing an extension and cannot be auto-detected',
                    $filename
                ));
            }
    
            $extension = strtolower($pathinfo['extension']);
            // Only $extension is used beyond that point


--------


.. _thinkphp-php-usepathinfoargs:

ThinkPHP
^^^^^^^^

:ref:`use-pathinfo()-arguments`, in ThinkPHP/Extend/Library/ORG/Net/UploadFile.class.php:508. 

Without any other check, pathinfo() could be used with PATHINFO_EXTENSION.

.. code-block:: php

    private function getExt($filename) {
            $pathinfo = pathinfo($filename);
            return $pathinfo['extension'];
        }

Slice Arrays First
==================

.. _wordpress-arrays-slicefirst:

WordPress
^^^^^^^^^

:ref:`slice-arrays-first`, in /modules/InboundEmail/InboundEmail.php:1080. 

Instead of reading ALL the keys, and then, keeping only the first fifty, why not read the 50 first items from the array, and then extract the keys?

.. code-block:: php

    $results = array_slice(array_keys($diff), 0 ,50);

Double array_flip()
===================

.. _nextcloud-performances-doublearrayflip:

NextCloud
^^^^^^^^^

:ref:`double-array\_flip()`, in lib/public/AppFramework/Http/EmptyContentSecurityPolicy.php:372. 

The array $allowedScriptDomains is flipped, to unset 'self', then, unflipped (or flipped again), to restore its initial state. Using array_keys() or array_search() would yield the needed keys for unsetting, at a lower cost.

.. code-block:: php

    if(is_string($this->useJsNonce)) {
    				$policy .= '\'nonce-'.base64_encode($this->useJsNonce).'\'';
    				$allowedScriptDomains = array_flip($this->allowedScriptDomains);
    				unset($allowedScriptDomains['\'self\'']);
    				$this->allowedScriptDomains = array_flip($allowedScriptDomains);
    				if(count($allowedScriptDomains) !== 0) {
    					$policy .= ' ';
    				}
    			}

Compare Hash
============

.. _traq-security-comparehash:

Traq
^^^^

:ref:`compare-hash`, in src/Models/User.php:105. 

This code should also avoid using SHA1. 

.. code-block:: php

    sha1($password) == $this->password


--------


.. _livezilla-security-comparehash:

LiveZilla
^^^^^^^^^

:ref:`compare-hash`, in livezilla/_lib/objects.global.users.inc.php:1391. 

This code is using the stronger SHA256 but compares it to another string. $_token may be non-empty, and still be comparable to 0. 

.. code-block:: php

    function IsValidToken($_token)
    {
        if(!empty($_token))
            if(hash("sha256",$this->Token) == $_token)
                return true;
        return false;
    }

Register Globals
================

.. _teampass-security-registerglobals:

TeamPass
^^^^^^^^

:ref:`register-globals`, in api/index.php:25. 

The API starts with security features, such as the whitelist(). The whitelist applies to IP addresses, so the query string is not sanitized. Then, the QUERY_STRING is parsed, and creates a lot of new global variables.

.. code-block:: php

    teampass_whitelist();
    
    parse_str($_SERVER['QUERY_STRING']);
    $method = $_SERVER['REQUEST_METHOD'];
    $request = explode("/", substr(@$_SERVER['PATH_INFO'], 1));


--------


.. _xoops-security-registerglobals:

XOOPS
^^^^^

:ref:`register-globals`, in htdocs/modules/system/admin/images/main.php:33:33. 

This code only exports the POST variables as globals. And it does clean incoming variables, but not all of them. 

.. code-block:: php

    // Check users rights
    if (!is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid())) {
        exit(_NOPERM);
    }
    
    //  Check is active
    if (!xoops_getModuleOption('active_images', 'system')) {
        redirect_header('admin.php', 2, _AM_SYSTEM_NOTACTIVE);
    }
    
    if (isset($_POST)) {
        foreach ($_POST as $k => $v) {
            ${$k} = $v;
        }
    }
    
    // Get Action type
    $op = system_CleanVars($_REQUEST, 'op', 'list', 'string');

Unserialize Second Arg
======================

.. _piwigo-security-unserializesecondarg:

Piwigo
^^^^^^

:ref:`unserialize-second-arg`, in admin/configuration.php:491. 

unserialize() extracts information from the $conf variable : this variable is read from a configuration file. It is later tested to be an array, whose index may not be all set (@$disabled[$type];). It would be safer to make $disabled an object, add the class to unserialize, and set default values to the needed properties/index. 

.. code-block:: php

    $disabled = @unserialize(@$conf['disabled_derivatives']);


--------


.. _livezilla-security-unserializesecondarg:

LiveZilla
^^^^^^^^^

:ref:`unserialize-second-arg`, in livezilla/_lib/objects.global.inc.php:2600. 

unserialize() only extract a non-empty value here. But its content is not checked. It is later used as an array, with multiple index. 

.. code-block:: php

    $this->Customs = (!empty($_row["customs"])) ? @unserialize($_row["customs"]) : array();

Isset Multiple Arguments
========================

.. _thinkphp-php-issetmultipleargs:

ThinkPHP
^^^^^^^^

:ref:`isset-multiple-arguments`, in library/think/Request.php:1187. 

This may be shortened with isset($sub), $array[$name][$sub])

.. code-block:: php

    isset($sub) && isset($array[$name][$sub])


--------


.. _livezilla-php-issetmultipleargs:

LiveZilla
^^^^^^^^^

:ref:`isset-multiple-arguments`, in livezilla/_lib/trdp/pchart/class/pDraw.class.php:3852. 

This is the equivalent of !(isset($Data["Series"][$SerieA]["Data"]) && isset($Data["Series"][$SerieB]["Data"])), and then, !(isset($Data["Series"][$SerieA]["Data"], $Data["Series"][$SerieB]["Data"]))

.. code-block:: php

    !isset($Data["Series"][$SerieA]["Data"]) || !isset($Data["Series"][$SerieB]["Data"])

Use List With Foreach
=====================

.. _mediawiki-structures-uselistwithforeach:

MediaWiki
^^^^^^^^^

:ref:`use-list-with-foreach`, in includes/parser/LinkHolderArray.php:372. 

This foreach reads each element from $entries into entry. $entry, in turn, is written into $pdbk, $title and $displayText for easier reuse. 5 elements are read from $entry, and they could be set in their respective variable in the foreach() with a list call. The only on that can't be set is 'query' which has to be tested.

.. code-block:: php

    foreach ( $entries as $index => $entry ) {
    				$pdbk = $entry['pdbk'];
    				$title = $entry['title'];
    				$query = isset( $entry['query'] ) ? $entry['query'] : [];
    				$key = "$ns:$index";
    				$searchkey = "<!--LINK'\" $key-->";
    				$displayText = $entry['text'];
    				if ( isset( $entry['selflink'] ) ) {
    					$replacePairs[$searchkey] = Linker::makeSelfLinkObj( $title, $displayText, $query );
    					continue;
    				}
    				if ( $displayText === '' ) {
    					$displayText = null;
    				} else {
    					$displayText = new HtmlArmor( $displayText );
    				}
    				if ( !isset( $colours[$pdbk] ) ) {
    					$colours[$pdbk] = 'new';
    				}
    				$attribs = [];
    				if ( $colours[$pdbk] == 'new' ) {
    					$linkCache->addBadLinkObj( $title );
    					$output->addLink( $title, 0 );
    					$link = $linkRenderer->makeBrokenLink(
    						$title, $displayText, $attribs, $query
    					);
    				} else {
    					$link = $linkRenderer->makePreloadedLink(
    						$title, $displayText, $colours[$pdbk], $attribs, $query
    					);
    				}
    
    				$replacePairs[$searchkey] = $link;
    			}

Drop Substr Last Arg
====================

.. _suitecrm-structures-substrlastarg:

SuiteCrm
^^^^^^^^

:ref:`drop-substr-last-arg`, in modules/UpgradeWizard/uw_utils.php:2422. 

substr() is even trying to go beyond the end of the string. 

.. code-block:: php

    substr($relativeFile, 1, strlen($relativeFile))


--------


.. _tine20-structures-substrlastarg:

Tine20
^^^^^^

:ref:`drop-substr-last-arg`, in tine20/Calendar/Frontend/Cli.php:95. 

Omitting the last character would yield the same result.

.. code-block:: php

    substr($opt, 18, strlen($opt))

Possible Increment
==================

.. _zurmo-structures-possibleincrement:

Zurmo
^^^^^

:ref:`possible-increment`, in app/protected/modules/workflows/utils/SavedWorkflowsUtil.php:196. 

There are suspicious extra spaces around the +, that give the hint that there used to be something else, like a constant, there. From the name of the methods, it seems that this code was refactored from an addition to a simple method call. 

.. code-block:: php

    $timeStamp =  + $workflow->getTimeTrigger()->resolveNewTimeStampForDuration(time());


--------


.. _mediawiki-structures-possibleincrement:

MediaWiki
^^^^^^^^^

:ref:`possible-increment`, in includes/filerepo/file/LocalFile.php:613. 

That is a useless assignation, except for the transtyping to integer that PHP does silently. May be that should be a +=, or completely dropped.

.. code-block:: php

    $decoded[$field] = +$decoded[$field]

One If Is Sufficient
====================

.. _tikiwiki-structures-oneifissufficient:

Tikiwiki
^^^^^^^^

:ref:`one-if-is-sufficient`, in /lib/wiki-plugins/wikiplugin_trade.php:152. 

empty($params['inputtitle']) should have priority over $params['wanted'] == 'n'.

.. code-block:: php

    if ($params['wanted'] == 'n') {
    		if (empty($params['inputtitle'])) {
    			$params['inputtitle'] = 'Payment of %0 %1 from user %2 to %3';
    		}
    	} else {
    		if (empty($params['inputtitle'])) {
    			$params['inputtitle'] = 'Request payment of %0 %1 to user %2 from %3';
    		}
    	}

Could Use array_unique
======================

.. _dolibarr-structures-couldusearrayunique:

Dolibarr
^^^^^^^^

:ref:`could-use-array\_unique`, in /htdocs/includes/restler/framework/Luracast/Restler/Format/XmlFormat.php:250. 

This loop has two distinct operations : the first collect keys and keep them unique. A combinaison of array_keys() and array_unique() would do that job, while saving the in_array() lookup, and the configuration check with 'static::$importSettingsFromXml'. The second operation is distinct, and could be done with array_map().

.. code-block:: php

    $attributes = $xml->attributes();
                foreach ($attributes as $key => $value) {
                    if (static::$importSettingsFromXml
                        && !in_array($key, static::$attributeNames)
                    ) {
                        static::$attributeNames[] = $key;
                    }
                    $r[$key] = static::setType((string)$value);
                }


--------


.. _openemr-structures-couldusearrayunique:

OpenEMR
^^^^^^^

:ref:`could-use-array\_unique`, in gacl/gacl_api.class.php:441:441. 

This loop is quite complex : it collects $aro_value in $acl_array['aro'][$aro_section_value], but also creates the array in $acl_array['aro'][$aro_section_value], and report errors in the debug log. array_unique() could replace the collection, while the debug would have to be done somewhere else.

.. code-block:: php

    foreach ($aro_value_array as $aro_value) {
    					if ( count($acl_array['aro'][$aro_section_value]) != 0 ) {
    						if (!in_array($aro_value, $acl_array['aro'][$aro_section_value])) {
    							$this->debug_text("append_acl(): ARO Section Value: $aro_section_value ARO VALUE: $aro_value");
    							$acl_array['aro'][$aro_section_value][] = $aro_value;
    							$update=1;
    						} else {
    							$this->debug_text("append_acl(): Duplicate ARO, ignoring... ");
    						}
    					} else { //Array is empty so add this aro value.
    						$acl_array['aro'][$aro_section_value][] = $aro_value;
    						$update = 1;
    					}
    				}

Should Use Operator
===================

.. _zencart-structures-shoulduseoperator:

Zencart
^^^^^^^

:ref:`should-use-operator`, in /includes/modules/payment/paypal/paypal_curl.php:378. 

Here, $options is merged with $values if it is an array. If it is not an array, it is probably a null value, and may be ignored. Adding a 'array' typehint will strengthen the code an catch situations where TransactionSearch() is called with a string, leading to clearer code.

.. code-block:: php

    function TransactionSearch($startdate, $txnID = '', $email = '', $options) {
        // several lines of code, no mention of $options
          if (is_array($options)) $values = array_merge($values, $options);
        }
        return $this->_request($values, 'TransactionSearch');
      }


--------


.. _sugarcrm-structures-shoulduseoperator:

SugarCrm
^^^^^^^^

:ref:`should-use-operator`, in include/utils.php:2093:464. 

$override should an an array : if not, it is actually set by default to empty array. Here, a typehint with a default value of 'array()' would offset the parameter validation to the calling method.

.. code-block:: php

    function sugar_config_union( $default, $override ){
    	// a little different then array_merge and array_merge_recursive.  we want
    	// the second array to override the first array if the same value exists,
    	// otherwise merge the unique keys.  it handles arrays of arrays recursively
    	// might be suitable for a generic array_union
    	if( !is_array( $override ) ){
    		$override = array();
    	}
    	foreach( $default as $key => $value ){
    		if( !array_key_exists($key, $override) ){
    			$override[$key] = $value;
    		}
    		else if( is_array( $key ) ){
    			$override[$key] = sugar_config_union( $value, $override[$key] );
    		}
    	}
    	return( $override );
    }

Could Be Typehinted Callable
============================

.. _magento-functions-couldbecallable:

Magento
^^^^^^^

:ref:`could-be-typehinted-callable`, in wp-admin/includes/misc.php:74. 

$objMethod argument is used to call a function, a method or a localmethod. The typehint would save the middle condition, and make a better job than 'is_array' to check if $objMethod is callable. Yet, the final 'else' means that $objMethod is also the name of a method, and PHP won't validate this, unless there is a function with the same name. Here, callable is not an option. 

.. code-block:: php

    public function each($objMethod, $args = [])
        {
            if ($objMethod instanceof \Closure) {
                foreach ($this->getItems() as $item) {
                    $objMethod($item, ...$args);
                }
            } elseif (is_array($objMethod)) {
                foreach ($this->getItems() as $item) {
                    call_user_func($objMethod, $item, ...$args);
                }
            } else {
                foreach ($this->getItems() as $item) {
                    $item->$objMethod(...$args);
                }
            }
        }


--------


.. _prestashop-functions-couldbecallable:

PrestaShop
^^^^^^^^^^

:ref:`could-be-typehinted-callable`, in wp-admin/includes/misc.php:74. 

$funcname is tested with is_callable() before being used as a method. Typehint callable would reduce the size of the code. 

.. code-block:: php

    public static function arrayWalk(&$array, $funcname, &$user_data = false)
    	{
    		if (!is_callable($funcname)) return false;
    
    		foreach ($array as $k => $row)
    			if (!call_user_func_array($funcname, array($row, $k, $user_data)))
    				return false;
    		return true;
    	}

Named Regex
===========

.. _phinx-structures-namedregex:

Phinx
^^^^^

:ref:`named-regex`, in src/Phinx/Util/Util.php:127. 

$matches[1] could be renamed by $matches['filename'], if the capturing subpattern was named 'filename'. 

.. code-block:: php

    const MIGRATION_FILE_NAME_PATTERN = '/^\d+_([\w_]+).php$/i';
    //.... More code with class definition
        public static function mapFileNameToClassName($fileName)
        {
            $matches = [];
            if (preg_match(static::MIGRATION_FILE_NAME_PATTERN, $fileName, $matches)) {
                $fileName = $matches[1];
            }
    
            return str_replace(' ', '', ucwords(str_replace('_', ' ', $fileName)));
        }


--------


.. _shopware-structures-namedregex:

shopware
^^^^^^^^

:ref:`named-regex`, in engine/Library/Enlight/Components/Snippet/Resource.php:207. 

$_match[3] is actually extracted two preg_match() before : by the time we read its usage, the first regex has been forgotten. A named subpattern would be useful here to remember what was captured.

.. code-block:: php

    if (!preg_match("!(.?)(name=)(.*?)(?=(\s|$))!", $_block_args, $_match) && empty($_block_default)) {
                    throw new SmartyException('"' . $_block_tag . '" missing name attribute');
                }
                $_block_force = (bool) preg_match('#[\s]force#', $_block_args);
                $_block_json = (bool) preg_match('#[\s]json=["\']true["\']\W#', $_block_args);
                $_block_name = !empty($_match[3]) ? trim($_match[3], '\'"') : $_block_default;


