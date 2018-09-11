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

Several Instructions On The Same Line
=====================================

.. _piwigo-structures-onelinetwoinstructions:

Piwigo
^^^^^^

:ref:`several-instructions-on-the-same-line`, in tools/triggers_list.php:993. 

There are two instructions on the line with the if(). Note that the condition is not followed by a bracketed block. With a quick review, it really seems that echo '<br>' and $f=0; are on the same block, but the second is indeed an unconditional expression. This is very difficult to spot. 

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

The condition checks first if $has_templates or $theme->parent(), and one of the two is sufficient to be valid. Then, it checks again that $theme->parent() is activated with &&. This condition may be reduced to simply calling $theme->parent(), as $has_template is unused here.

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

In this code, is_object() and instanceof have the same basic : they both check that $ts is an object. In fact, instanceof is more precise, and give more information about the variable. 

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

In this code, is_object() is used to check the status of the order. Possibly, $order is false or null in case of incompatible status. Yet, when $object is an object, and in particular being a global that may be assigned anywhere else in the code, it seems that the method 'update_status' is magically always available. Here, using instance of to make sure that $order is an 'paypal' class, or a 'storepickup' or any of the payment class.  

.. code-block:: php

    function __construct() {
        global $order;
    
        // more lines, no mention of $order
        if (is_object($order)) $this->update_status();
    
        // more code
    }

Cast To Boolean
===============

.. _mediawiki-structures-casttoboolean:

MediaWiki
^^^^^^^^^

:ref:`cast-to-boolean`, in includes/page/WikiPage.php:2274. 

$options['changed'] and $options['created'] are documented and used as boolean. Yet, SiteStatsUpdate may require integers, for correct storage in the database, hence the type casting. (int) (bool) may be an alternative here.

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

Several cases are built on the same pattern there. Each of the expression may be simply cast to (bool).

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

Humo-Gen
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

edusoho
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

Avoid glob() Usage
==================

.. _phinx-performances-noglob:

Phinx
^^^^^

:ref:`avoid-glob()-usage`, in src/Phinx/Migration/Manager.php:362. 

Glob() searches for a list of files in the migration folder. Those files are not known, but they have a format, as checked later with the regex : a combinaison of FilesystemIterator and RegexIterator would do the trick too.

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


--------


.. _swoole-structures-uselistwithforeach:

Swoole
^^^^^^

:ref:`use-list-with-foreach`, in libs/Swoole/SelectDB.php:848. 

This foreach reads 'c' in the $c variable (via the $_c). It could be simplified with foreach($c as ['c' => $d]) { $cc += $d; }. In fact, it could very well be replaced by array_sum() altogether.

.. code-block:: php

    $cc = 0;
                foreach ($c as $_c)
                {
                    $cc += $_c['c'];
                }

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


