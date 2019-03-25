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

Ambiguous Array Index
=====================

.. _prestashop-arrays-ambiguouskeys:

PrestaShop
^^^^^^^^^^

:ref:`ambiguous-array-index`, in src/PrestaShopBundle/Install/Install.php:532. 

Null, as a key, is actually the empty string. 

.. code-block:: php

    $list = array(
                'products' => _PS_PROD_IMG_DIR_,
                'categories' => _PS_CAT_IMG_DIR_,
                'manufacturers' => _PS_MANU_IMG_DIR_,
                'suppliers' => _PS_SUPP_IMG_DIR_,
                'stores' => _PS_STORE_IMG_DIR_,
                null => _PS_IMG_DIR_.'l/', // Little trick to copy images in img/l/ path with all types
            );


--------


.. _mautic-arrays-ambiguouskeys:

Mautic
^^^^^^

:ref:`ambiguous-array-index`, in app/bundles/CoreBundle/Entity/CommonRepository.php:314. 

True is turned into 1 (integer), and false is turned into 0 (integer). 

.. code-block:: php

    foreach ($metadata->getAssociationMappings() as $field => $association) {
                        if (in_array($association['type'], [ClassMetadataInfo::ONE_TO_ONE, ClassMetadataInfo::MANY_TO_ONE])) {
                            $baseCols[true][$entityClass][]  = $association['joinColumns'][0]['name'];
                            $baseCols[false][$entityClass][] = $field;
                        }
                    }

error_reporting() With Integers
===============================

.. _sugarcrm-structures-errorreportingwithinteger:

SugarCrm
^^^^^^^^

:ref:`error\_reporting()-with-integers`, in modules/UpgradeWizard/silentUpgrade_step1.php:436. 

This only displays E_ERROR, the highest level of error reporting. It should be checked, as it happens in the 'silentUpgrade' script. 

.. code-block:: php

    ini_set('error_reporting', 1);

Eval() Usage
============

.. _xoops-structures-evalusage:

XOOPS
^^^^^

:ref:`eval()-usage`, in htdocs/modules/system/class/block.php:266. 

eval() execute code that was arbitrarily stored in $this, in one of the properties. Then, it is sent to output, but collected before reaching the browser, and put again in $content. May be the echo/ob_get_contents() could have been skipped.

.. code-block:: php

    ob_start();
                        echo eval($this->getVar('content', 'n'));
                        $content = ob_get_contents();
                        ob_end_clean();


--------


.. _mautic-structures-evalusage:

Mautic
^^^^^^

:ref:`eval()-usage`, in app/bundles/InstallBundle/Configurator/Step/CheckStep.php:238. 

create_function() is actually an eval() in disguise : replace it with a closure for code modernization

.. code-block:: php

    create_function('$cfgValue', 'return $cfgValue > 100;')

Multiply By One
===============

.. _sugarcrm-structures-multiplybyone:

SugarCrm
^^^^^^^^

:ref:`multiply-by-one`, in SugarCE-Full-6.5.26/modules/Relationships/views/view.editfields.php:74. 

Here, '$count % 1' is always true, after the first loop of the foreach. There is no need for % usage.

.. code-block:: php

    $count = 0;
            foreach($this->fields as $def)
            {
                if (!empty($def['relationship_field'])) {
                    $label = !empty($def['vname']) ? $def['vname'] : $def['name'];
                    echo <td> . translate($label, $this->module) . :</td>
                       . <td><input id='{$def['name']}' name='{$def['name']}'>  ;
    
                    if ($count%1)
                        echo </tr><tr>;
                    $count++;
                }
            }
            echo </tr></table></form>;


--------


.. _edusoho-structures-multiplybyone:

Edusoho
^^^^^^^

:ref:`multiply-by-one`, in wp-admin/includes/misc.php:74. 

1 is useless here, since 24 * 3600 is already an integer. And, of course, a day is not 24 * 3600... at least every day.

.. code-block:: php

    'yesterdayStart' => date('Y-m-d', strtotime(date('Y-m-d', time())) - 1 * 24 * 3600),

Not Not
=======

.. _cleverstyle-structures-notnot:

Cleverstyle
^^^^^^^^^^^

:ref:`not-not`, in modules/OAuth2/OAuth2.php:190. 

This double-call returns ``$results`` as a boolean, preventing a spill of data to the calling method. The ``(bool)`` operator would be clearer here.

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

:ref:`not-not`, in tine20/Calendar/Controller/MSEventFacade.php:392. 

It seems that !! is almost superfluous, as a property called 'is_deleted' should already be a boolean.

.. code-block:: php

    foreach ($exceptions as $exception) {
                    $exception->assertAttendee($this->getCalendarUser());
                    $this->_prepareException($savedEvent, $exception);
                    $this->_preserveMetaData($savedEvent, $exception, true);
                    $this->_eventController->createRecurException($exception, !!$exception->is_deleted);
                }

include_once() Usage
====================

.. _xoops-structures-onceusage:

XOOPS
^^^^^

:ref:`include\_once()-usage`, in /htdocs/xoops_lib/modules/protector/admin/center.php:5. 

Loading() classes should be down with autoload(). autload() may be build in several distinct functions, using spl_autoload_register().

.. code-block:: php

    require_once dirname(__DIR__) . 'class/gtickets.php'


--------


.. _tikiwiki-structures-onceusage:

Tikiwiki
^^^^^^^^

:ref:`include\_once()-usage`, in tiki-mytiki_shared.php :140. 

Turn the code from tiki-mytiki_shared.php into a function or a method, and call it when needed. 

.. code-block:: php

    include_once('tiki-mytiki_shared.php');

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

var_dump()... Usage
===================

.. _tine20-structures-vardumpusage:

Tine20
^^^^^^

:ref:`var\_dump()...-usage`, in tine20/library/Ajam/Connection.php:122. 

Two usage of var_dump(). They are protected by configuration, since the debug property must be set to 'true'. Yet, it is safer to avoid them altogether, and log the information to an external file.

.. code-block:: php

    if($this->debug === true) {
                var_dump($this->getLastRequest());
                var_dump($response);
            }


--------


.. _piwigo-structures-vardumpusage:

Piwigo
^^^^^^

:ref:`var\_dump()...-usage`, in include/ws_core.inc.php:273. 

This is a hidden debug system : when the response format is not available, the whole object is dumped in the output.

.. code-block:: php

    function run()
      {
        if ( is_null($this->_responseEncoder) )
        {
          set_status_header(400);
          @header("Content-Type: text/plain");
          echo ("Cannot process your request. Unknown response format.
    Request format: ".@$this->_requestFormat." Response format: ".@$this->_responseFormat."\n");
          var_export($this);
          die(0);
        }

Empty Function
==============

.. _contao-functions-emptyfunction:

Contao
^^^^^^

:ref:`empty-function`, in core-bundle/src/Resources/contao/modules/ModuleQuicklink.php:91. 

The closure used with array_map() is empty : this means that the keys are all set to the returned value of the empty closure, which is null. The actual effect is to reset the values to NULL. A better solution, without using the empty closure, is to rely on array_fill_keys() to create an array with default values.  

.. code-block:: php

    if (!empty($tmp) && \is_array($tmp))
    			{
    				$arrPages = array_map(function () {}, array_flip($tmp));
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

Non Static Methods Called In A Static
=====================================

.. _dolphin-classes-nonstaticmethodscalledstatic:

Dolphin
^^^^^^^

:ref:`non-static-methods-called-in-a-static`, in Dolphin-v.7.3.5/xmlrpc/BxDolXMLRPCFriends.php:11. 

getIdByNickname() is indeed defined in the class 'BxDolXMLRPCUtil' and it calls the database. The class relies on functions (not methods) to query the database with the correct connexion. 

.. code-block:: php

    class BxDolXMLRPCFriends
    {
        function getFriends($sUser, $sPwd, $sNick, $sLang)
        {
            $iIdProfile = BxDolXMLRPCUtil::getIdByNickname ($sNick);


--------


.. _magento-classes-nonstaticmethodscalledstatic:

Magento
^^^^^^^

:ref:`non-static-methods-called-in-a-static`, in app/code/core/Mage/Paypal/Model/Payflowlink.php:143. 

Mage_Payment_Model_Method_Abstract is an abstract class : this way, it is not possible to instantiate it and then, access its methods. The class is extended, so it could be called from one of the objects. Although, the troubling part is that isAvailable() uses $this, so it can't be static. 

.. code-block:: php

    Mage_Payment_Model_Method_Abstract::isAvailable($quote)

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

Static Methods Can't Contain $this
==================================

.. _xataface-classes-staticcontainsthis:

xataface
^^^^^^^^

:ref:`static-methods-can't-contain-$this`, in Dataface/LanguageTool.php:48. 

$this is hidden in the arguments of the static call to the method.

.. code-block:: php

    public static function loadRealm($name){
    		return self::getInstance($this->app->_conf['default_language'])->loadRealm($name);
    	}


--------


.. _sugarcrm-classes-staticcontainsthis:

SugarCrm
^^^^^^^^

:ref:`static-methods-can't-contain-$this`, in SugarCE-Full-6.5.26/modules/ACLActions/ACLAction.php:332. 

Notice how $this is tested for existence before using it. It seems strange, at first, but we have to remember that if $this is never set when calling a static method, a static method may be called with $this. Confusingly, this static method may be called in two ways. 

.. code-block:: php

    static function hasAccess($is_owner=false, $access = 0){
    
            if($access != 0 && $access == ACL_ALLOW_ALL || ($is_owner && $access == ACL_ALLOW_OWNER))return true;
           //if this exists, then this function is not static, so check the aclaccess parameter
            if(isset($this) && isset($this->aclaccess)){
                if($this->aclaccess == ACL_ALLOW_ALL || ($is_owner && $this->aclaccess == ACL_ALLOW_OWNER))
                return true;
            }
            return false;
        }

While(List() = Each())
======================

.. _openemr-structures-whilelisteach:

OpenEMR
^^^^^^^

:ref:`while(list()-=-each())`, in library/report.inc:153. 

The first while() is needed, to read the arbitrary long list returned by the SQL query. The second list may be upgraded with a foreach, to read both the key and the value. This is certainly faster to execute and to read.

.. code-block:: php

    function getInsuranceReport($pid, $type = primary)
    {
        $sql = select * from insurance_data where pid=? and type=? order by date ASC;
        $res = sqlStatement($sql, array($pid, $type));
        while ($list = sqlFetchArray($res)) {
            while (list($key, $value) = each($list)) {
                if ($ret[$key]['content'] != $value && $ret[$key]['date'] < $list['date']) {
                    $ret[$key]['content'] = $value;
                    $ret[$key]['date'] = $list['date'];
                }
            }
        }
    
        return $ret;
    }


--------


.. _dolphin-structures-whilelisteach:

Dolphin
^^^^^^^

:ref:`while(list()-=-each())`, in Dolphin-v.7.3.5/modules/boonex/forum/classes/Forum.php:1875. 

This clever use of while() and list() is actually a foreach($a as $r) (the keys are ignored)

.. code-block:: php

    function getRssUpdatedTopics ()
        {
            global $gConf;
    
            $this->_rssPrepareConf ();
    
            $a = $this->fdb->getRecentTopics (0);
    
            $items = '';
            $lastBuildDate = '';
            $ui = array();
            reset ($a);
            while ( list (,$r) = each ($a) ) {
                // acquire user info
                if (!isset($ui[$r['last_post_user']]) && ($aa = $this->_getUserInfoReadyArray ($r['last_post_user'], false)))
                    $ui[$r['last_post_user']] = $aa;
    
                $td = orca_mb_replace('/#/', $r['count_posts'], '[L[# posts]]') . ' &#183; ' . orca_mb_replace('/#/', $ui[$r['last_post_user']]['title'], '[L[last reply by #]]') . ' &#183; ' . $r['cat_name'] . ' &#187; ' . $r['forum_title'];

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

$this Belongs To Classes Or Traits
==================================

.. _openemr-classes-thisisforclasses:

OpenEMR
^^^^^^^

:ref:`$this-belongs-to-classes-or-traits`, in ccr/display.php:24. 

$this is used to call the document_upload_download_log() method, although this piece of code is not part of a class, nor is included in a class.

.. code-block:: php

    <?php 
    require_once(dirname(__FILE__) . "/../interface/globals.php");
    
    $type = $_GET['type'];
    $document_id = $_GET['doc_id'];
    $d = new Document($document_id);
    $url =  $d->get_url();
    $storagemethod = $d->get_storagemethod();
    $couch_docid = $d->get_couch_docid();
    $couch_revid = $d->get_couch_revid();
    
    if ($couch_docid && $couch_revid) {
        $couch = new CouchDB();
        $data = array($GLOBALS['couchdb_dbase'],$couch_docid);
        $resp = $couch->retrieve_doc($data);
        $xml = base64_decode($resp->data);
        if ($content=='' && $GLOBALS['couchdb_log']==1) {
            $log_content = date('Y-m-d H:i:s')." ==> Retrieving document\r\n";
            $log_content = date('Y-m-d H:i:s')." ==> URL: ".$url."\r\n";
            $log_content .= date('Y-m-d H:i:s')." ==> CouchDB Document Id: ".$couch_docid."\r\n";
            $log_content .= date('Y-m-d H:i:s')." ==> CouchDB Revision Id: ".$couch_revid."\r\n";
            $log_content .= date('Y-m-d H:i:s')." ==> Failed to fetch document content from CouchDB.\r\n";
            //$log_content .= date('Y-m-d H:i:s')." ==> Will try to download file from HardDisk if exists.\r\n\r\n";
            $this->document_upload_download_log($d->get_foreign_id(), $log_content);
            die(xlt("File retrieval from CouchDB failed"));
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

Class, Interface Or Trait With Identical Names
==============================================

.. _shopware-classes-citsamename:

shopware
^^^^^^^^

:ref:`class,-interface-or-trait-with-identical-names`, in engine/Shopware/Components/Form/Interfaces/Element.php:30. 

Most Element classes extends ModelEntity, which is an abstract class. There is also an interface, called Element, for forms. And, last, one of the class Element extends JsonSerializable, which is a PHP native interface. Namespaces are definitely crucial to understand which Element is which. 

.. code-block:: php

    interface Element { /**/ } // in engine/Shopware/Components/Form/Interfaces/Element.php:30
    
    class Element implements \JsonSerializable { /**/ } 	// in engine/Shopware/Bundle/EmotionBundle/Struct/Element.php:29
    
    class Element extends ModelEntity { /**/ } 	// in /engine/Shopware/Models/Document/Element.php:37


--------


.. _nextcloud-classes-citsamename:

NextCloud
^^^^^^^^^

:ref:`class,-interface-or-trait-with-identical-names`, in lib/private/Files/Storage/Storage.php:33. 

Interface Storage extends another Storage class. Here, the fully qualified name is used, so we can understand which storage is which at read time : a 'use' alias would make this line more confusing.

.. code-block:: php

    interface Storage extends \OCP\Files\Storage { /**/ }

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

Used Once Variables (In Scope)
==============================

.. _shopware-variables-variableusedoncebycontext:

shopware
^^^^^^^^

:ref:`used-once-variables-(in-scope)`, in _sql/migrations/438-add-email-template-header-footer-fields.php:115. 

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

Aliases Usage
=============

.. _cleverstyle-functions-aliasesusage:

Cleverstyle
^^^^^^^^^^^

:ref:`aliases-usage`, in modules/HybridAuth/Hybrid/thirdparty/Vimeo/Vimeo.php:422. 

is_writeable() should be written is_writable(). No extra 'e'. 

.. code-block:: php

    is_writeable($chunk_temp_dir)


--------


.. _phpmyadmin-functions-aliasesusage:

phpMyAdmin
^^^^^^^^^^

:ref:`aliases-usage`, in libraries/classes/Server/Privileges.php:5064. 

join() should be written implode()

.. code-block:: php

    join('`, `', $tmp_privs2['Update'])

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

Undefined static:: Or self::
============================

.. _xataface-classes-undefinedstaticmp:

xataface
^^^^^^^^

:ref:`undefined-static\:\:-or-self\:\:`, in actions/forgot_password.php:194. 

This is probably a typo, since the property called 	public static $EX_NO_USERS_WITH_EMAIL = 501; is defined in that class. 

.. code-block:: php

    if ( !$user ) throw new Exception(df_translate('actions.forgot_password.null_user',"Cannot send email for null user"), self::$EX_NO_USERS_FOUND_WITH_EMAIL);


--------


.. _sugarcrm-classes-undefinedstaticmp:

SugarCrm
^^^^^^^^

:ref:`undefined-static\:\:-or-self\:\:`, in actions/forgot_password.php:194. 

self::$sugar_strptime_long_mon refers to the current class, which extends DateTime. No static property was defined at either of them, with the name '$sugar_strptime_long_mon'. This has been a Fatal error at execution time since PHP 5.3, at least. 

.. code-block:: php

    if ( isset($regexp['positions']['F']) && !empty($dateparts[$regexp['positions']['F']])) {
                           // FIXME: locale?
                $mon = $dateparts[$regexp['positions']['F']];
                if(isset(self::$sugar_strptime_long_mon[$mon])) {
                    $data["tm_mon"] = self::$sugar_strptime_long_mon[$mon];
                } else {
                    return false;
                }
            }

list() May Omit Variables
=========================

.. _openconf-structures-listomissions:

OpenConf
^^^^^^^^

:ref:`list()-may-omit-variables`, in openconf/author/privacy.php:29. 

The first variable in the list(), $none, isn't reused anywhere in the script. In fact, its name convey the meaning that is it useless, but is in the array nonetheless. 

.. code-block:: php

    list($none, $OC_privacy_policy) = oc_getTemplate('privacy_policy');


--------


.. _fuelcms-structures-listomissions:

FuelCMS
^^^^^^^

:ref:`list()-may-omit-variables`, in wp-admin/includes/misc.php:74. 

$a is never reused again. $b, on the other hand is. Not assigning any value to $a saves some memory, and avoid polluting the local variable space. 

.. code-block:: php

    list($b, $a) = array(reset($params->me), key($params->me));

Or Die
======

.. _tine20-structures-ordie:

Tine20
^^^^^^

:ref:`or-die`, in scripts/addgrant.php:34. 

Typical error handling, which also displays the MySQL error message, and leaks informations about the system. One may also note that mysql_connect is not supported anymore, and was replaced with mysqli_ and pdo : this may be a backward compatibile file.

.. code-block:: php

    $link = mysql_connect($host, $user, $pass) or die("No connection: " . mysql_error( ))


--------


.. _openconf-structures-ordie:

OpenConf
^^^^^^^^

:ref:`or-die`, in openconf/chair/export.inc:143. 

or die() is also applied to many situations, where a blocking situation arise. Here, with the creation of a temporary file.

.. code-block:: php

    $coreFile = tempnam('/tmp/', 'ocexport') or die('could not generate Excel file (6)')

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

Undefined Properties
====================

.. _wordpress-classes-undefinedproperty:

WordPress
^^^^^^^^^

:ref:`undefined-properties`, in wp-admin/includes/misc.php:74. 

Properties are not defined, but they are thoroughly initialized when the XML document is parsed. All those definition should be in a property definition, for clear documentation.

.. code-block:: php

    $this->DeliveryLine1 = '';
            $this->DeliveryLine2 = '';
            $this->City = '';
            $this->State = '';
            $this->ZipAddon = '';


--------


.. _mediawiki-classes-undefinedproperty:

MediaWiki
^^^^^^^^^

:ref:`undefined-properties`, in wp-admin/includes/misc.php:74. 

parsedParametersDeleteLog is an undefined property. Defining the property with a null default value is important here, to keep the code running. 

.. code-block:: php

    protected function getMessageParameters() {
    		if ( isset( $this->parsedParametersDeleteLog ) ) {
    			return $this->parsedParametersDeleteLog;
    		}

Strict Comparison With Booleans
===============================

.. _phinx-structures-booleanstrictcomparison:

Phinx
^^^^^

:ref:`strict-comparison-with-booleans`, in src/Phinx/Db/Adapter/MysqlAdapter.php:1131. 

`ìsNull( )`` always returns a boolean : it may be only be ``true`` or ``false``. Until typehinted properties or return typehint are used, isNull() may return anything else. 

.. code-block:: php

    $column->isNull( ) == false


--------


.. _typo3-structures-booleanstrictcomparison:

Typo3
^^^^^

:ref:`strict-comparison-with-booleans`, in typo3/sysext/lowlevel/Classes/Command/FilesWithMultipleReferencesCommand.php:90. 

When 'dry-run' is not defined, the getOption( ) method actually returns a ``null``value. So, comparing the result of getOption() to false is actually wrong : using a constant to prevent values to be inconsistent is recommended here.

.. code-block:: php

    $input->getOption('dry-run') != false

Lone Blocks
===========

.. _thinkphp-structures-loneblock:

ThinkPHP
^^^^^^^^

:ref:`lone-blocks`, in ThinkPHP/Library/Vendor/Hprose/HproseReader.php:163. 

There is no need for block in a case/default clause. PHP executes all command in order, until a break or the end of the switch. There is another occurrence of that situation in this code : it seems to be a coding convention, while only applied to a few switch statements.

.. code-block:: php

    for ($i = 0; $i < $len; ++$i) {
                switch (ord($this->stream->getc()) >> 4) {
                    case 0:
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                    case 6:
                    case 7: {
                        // 0xxx xxxx
                        $utf8len++;
                        break;
                    }
                    case 12:
                    case 13: {
                        // 110x xxxx   10xx xxxx
                        $this->stream->skip(1);
                        $utf8len += 2;
                        break;
                    }


--------


.. _tine20-structures-loneblock:

Tine20
^^^^^^

:ref:`lone-blocks`, in tine20/Addressbook/Convert/Contact/VCard/Abstract.php:199. 

A case of empty case, with empty blocks. This is useless code. Event the curly brackets with the final case are useless.

.. code-block:: php

    switch ( $property['TYPE'] ) {
                            case 'JPG' : {}
                            case 'jpg' : {}
                            case 'Jpg' : {}
                            case 'Jpeg' : {}
                            case 'jpeg' : {}
                            case 'PNG' : {}
                            case 'png' : {}
                            case 'JPEG' : {
                                if (Tinebase_Core::isLogLevel(Zend_Log::DEBUG)) 
                                    Tinebase_Core::getLogger()->warn(__METHOD__ . '::' . __LINE__ . ' Photo: passing on invalid ' . $property['TYPE'] . ' image as is (' . strlen($property->getValue()) .')' );
                                $jpegphoto = $property->getValue();
                                break;
                            }

PHP Keywords As Names
=====================

.. _churchcrm-php-reservednames:

ChurchCRM
^^^^^^^^^

:ref:`php-keywords-as-names`, in src/kiosk/index.php:42. 

$false may be true or false (or else...). In fact, the variable is not even defined in this file, and the file do a lot of inclusion. 

.. code-block:: php

    if (!isset($_COOKIE['kioskCookie'])) {
        if ($windowOpen) {
            $guid = uniqid();
            setcookie("kioskCookie", $guid, 2147483647);
            $Kiosk = new \ChurchCRM\KioskDevice();
            $Kiosk->setGUIDHash(hash('sha256', $guid));
            $Kiosk->setAccepted($false);
            $Kiosk->save();
        } else {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }
    }


--------


.. _xataface-php-reservednames:

xataface
^^^^^^^^

:ref:`php-keywords-as-names`, in Dataface/Record.php:1278. 

This one is documented, and in the end, makes a lot of sense.

.. code-block:: php

    function &getRelatedRecord($relationshipName, $index=0, $where=0, $sort=0){
    		if ( isset($this->cache[__FUNCTION__][$relationshipName][$index][$where][$sort]) ){
    			return $this->cache[__FUNCTION__][$relationshipName][$index][$where][$sort];
    		}
    		$it = $this->getRelationshipIterator($relationshipName, $index, 1, $where, $sort);
    		if ( $it->hasNext() ){
    			$rec =& $it->next();
    			$this->cache[__FUNCTION__][$relationshipName][$index][$where][$sort] =& $rec;
    			return $rec;
    		} else {
    			$null = null;	// stupid hack because literal 'null' can't be returned by ref.
    			return $null;
    		}
    	}

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

Catch Overwrite Variable
========================

.. _phpipam-structures-catchshadowsvariable:

PhpIPAM
^^^^^^^

:ref:`catch-overwrite-variable`, in app/subnets/scan/subnet-scan-snmp-route.php:58. 

$e is used both as 'local' variable : it is local to the catch clause, and it is a blind variable in a foreach(). There is little overlap between the two occurrences, but one reader may wonder why the caught exception is shown later on. 

.. code-block:: php

    try {
            $res = $Snmp->get_query(get_routing_table);
            // remove those not in subnet
            if (sizeof($res)>0) {
               // save for debug
               $debug[$d->hostname][$q] = $res;
    
               // save result
               $found[$d->id][$q] = $res;
            }
        } catch (Exception $e) {
           // save for debug
           $debug[$d->hostname][$q] = $res;
           $errors[] = $e->getMessage();
    	}
    
    // lots of code
    // on line 132
        // print errors
        if (isset($errors)) {
            print <hr>;
            foreach ($errors as $e) {
                print $Result->show (warning, $e, false, false, true);
            }
        }


--------


.. _suitecrm-structures-catchshadowsvariable:

SuiteCrm
^^^^^^^^

:ref:`catch-overwrite-variable`, in modules/Emails/EmailUIAjax.php:1082. 

$e starts as an Email(), in the 'getMultipleMessagesFromSugar' case, while a few lines later, in 'refreshSugarFolders', $e is now an exception. Breaks are in place, so both occurrences are separated, yet, one may wonder why an email is a warning, or a mail is a warning. 

.. code-block:: php

    // On line 900, $e is a Email
            case getMultipleMessagesFromSugar:
                $GLOBALS['log']->debug(********** EMAIL 2.0 - Asynchronous - at: getMultipleMessagesFromSugar);
                if (isset($_REQUEST['uid']) && !empty($_REQUEST['uid'])) {
                    $exIds = explode(,, $_REQUEST['uid']);
                    $out = array();
    
                    foreach ($exIds as $id) {
                        $e = new Email();
                        $e->retrieve($id);
                        $e->description_html = from_html($e->description_html);
                        $ie->email = $e;
                        $out[] = $ie->displayOneEmail($id, $_REQUEST['mbox']);
                    }
    
                    echo $json->encode($out);
                }
    
                break;
    
    
    // lots of code
    // on line 1082
            case refreshSugarFolders:
                try {
                    $GLOBALS['log']->debug(********** EMAIL 2.0 - Asynchronous - at: refreshSugarFolders);
                    $rootNode = new ExtNode('', '');
                    $folderOpenState = $current_user->getPreference('folderOpenState', 'Emails');
                    $folderOpenState = (empty($folderOpenState)) ?  : $folderOpenState;
                    $ret = $email->et->folder->getUserFolders(
                        $rootNode,
                        sugar_unserialize($folderOpenState),
                        $current_user,
                        true
                    );
                    $out = $json->encode($ret);
                    echo $out;
                } catch (SugarFolderEmptyException $e) {
                    $GLOBALS['log']->warn($e);
                    $out = $json->encode(array(
                        'message' => 'No folder selected warning message here...',
                    ));
                    echo $out;
                }
                break;

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

Objects Don't Need References
=============================

.. _zencart-structures-objectreferences:

Zencart
^^^^^^^

:ref:`objects-don't-need-references`, in includes/library/illuminate/support/helpers.php:484. 

No need for & operator when $class is only used for a method call.

.. code-block:: php

    /**
         * @param $class
         * @param $eventID
         * @param array $paramsArray
         */
        public function updateNotifyCheckoutflowFinishedManageSuccessOrderLinkEnd(&$class, $eventID, $paramsArray = array())
        {
            $class->getView()->getTplVarManager()->se('flag_show_order_link', false);
        }


--------


.. _xoops-structures-objectreferences:

XOOPS
^^^^^

:ref:`objects-don't-need-references`, in htdocs/class/theme_blocks.phps:221. 

Here, $template is modified, when its properties are modified. When only the properties are modified, or read, then & is not necessary.

.. code-block:: php

    public function buildBlock($xobject, &$template)
        {
            // The lame type workaround will change
            // bid is added temporarily as workaround for specific block manipulation
            $block = array(
                'id'      => $xobject->getVar('bid'),
                'module'  => $xobject->getVar('dirname'),
                'title'   => $xobject->getVar('title'),
                // 'name'        => strtolower( preg_replace( '/[^0-9a-zA-Z_]/', '', str_replace( ' ', '_', $xobject->getVar( 'name' ) ) ) ),
                'weight'  => $xobject->getVar('weight'),
                'lastmod' => $xobject->getVar('last_modified'));
    
            $bcachetime = (int)$xobject->getVar('bcachetime');
            if (empty($bcachetime)) {
                $template->caching = 0;
            } else {
                $template->caching        = 2;
                $template->cache_lifetime = $bcachetime;
            }
            $template->setCompileId($xobject->getVar('dirname', 'n'));
            $tplName = ($tplName = $xobject->getVar('template')) ? db:$tplName : 'db:system_block_dummy.tpl';
            $cacheid = $this->generateCacheId('blk_' . $xobject->getVar('bid'));
    // more code to the end of the method

Lost References
===============

.. _wordpress-variables-lostreferences:

WordPress
^^^^^^^^^

:ref:`lost-references`, in wp-admin/includes/misc.php:74. 

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    $markerdata = explode( "\n", implode( '', file( $filename ) ) );

Useless Global
==============

.. _zencart-structures-uselessglobal:

Zencart
^^^^^^^

:ref:`useless-global`, in admin/includes/modules/newsletters/newsletter.php:25. 

$_GET is always a global variable. There is no need to declare it global in any scope.

.. code-block:: php

    function choose_audience() {
          global $_GET;


--------


.. _humo-gen-structures-uselessglobal:

HuMo-Gen
^^^^^^^^

:ref:`useless-global`, in admin/includes/modules/newsletters/newsletter.php:25. 

It is hard to spot that $generY is useless, but this is the only occurrence where $generY is refered to as a global. It is not accessed anywhere else as a global (there are occurrences of $generY being an argument), and it is not even assigned within that function. 

.. code-block:: php

    function calculate_ancestor($pers) {
    global $db_functions, $reltext, $sexe, $sexe2, $spouse, $special_spouseY, $language, $ancestortext, $dutchtext, $selected_language, $spantext, $generY, $foundY_nr, $rel_arrayY;

Preprocessable
==============

.. _phpadsnew-structures-shouldpreprocess:

phpadsnew
^^^^^^^^^

:ref:`preprocessable`, in phpAdsNew-2.0/adview.php:302. 

Each call to chr() may be done before. First, chr() may be replace with the hexadecimal sequence "0x3B"; Secondly, 0x3b is a rather long replacement for a simple semi-colon. The whole pragraph could be stored in a separate file, for easier modifications. 

.. code-block:: php

    echo chr(0x47).chr(0x49).chr(0x46).chr(0x38).chr(0x39).chr(0x61).chr(0x01).chr(0x00).
    		     chr(0x01).chr(0x00).chr(0x80).chr(0x00).chr(0x00).chr(0x04).chr(0x02).chr(0x04).
    		 	 chr(0x00).chr(0x00).chr(0x00).chr(0x21).chr(0xF9).chr(0x04).chr(0x01).chr(0x00).
    		     chr(0x00).chr(0x00).chr(0x00).chr(0x2C).chr(0x00).chr(0x00).chr(0x00).chr(0x00).
    		     chr(0x01).chr(0x00).chr(0x01).chr(0x00).chr(0x00).chr(0x02).chr(0x02).chr(0x44).
    		     chr(0x01).chr(0x00).chr(0x3B);

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

Unresolved Instanceof
=====================

.. _wordpress-classes-unresolvedinstanceof:

WordPress
^^^^^^^^^

:ref:`unresolved-instanceof`, in wp-admin/includes/misc.php:74. 

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    private function resolveTag($match)
        {
            $tagReflector = $this->createLinkOrSeeTagFromRegexMatch($match);
            if (!$tagReflector instanceof Tag\SeeTag && !$tagReflector instanceof Tag\LinkTag) {
                return $match;
            }

Altering Foreach Without Reference
==================================

.. _contao-structures-alteringforeachwithoutreference:

Contao
^^^^^^

:ref:`altering-foreach-without-reference`, in core-bundle/src/Resources/contao/classes/Theme.php:613. 

$tmp[$kk] is &$vv.

.. code-block:: php

    foreach ($tmp as $kk=>$vv)
    								{
    									// Do not use the FilesModel here – tables are locked!
    									$objFile = $this->Database->prepare(SELECT uuid FROM tl_files WHERE path=?)
    															  ->limit(1)
    															  ->execute($this->customizeUploadPath($vv));
    
    									$tmp[$kk] = $objFile->uuid;
    								}


--------


.. _wordpress-structures-alteringforeachwithoutreference:

WordPress
^^^^^^^^^

:ref:`altering-foreach-without-reference`, in wp-admin/includes/misc.php:74. 

$ids[$index] is &$rrid. 

.. code-block:: php

    foreach($ids as $index => $rrid)
                    {
                        if($rrid == $this->Id)
                        {
                            $ids[$index] = $_id;
                            $write = true;
                            break;
                        }
                    }

Empty Instructions
==================

.. _zurmo-structures-emptylines:

Zurmo
^^^^^

:ref:`empty-instructions`, in app/protected/core/widgets/MentionInput.php:84. 

There is no need for a semi-colon after a if/then structure.

.. code-block:: php

    public function run()
            {
                $id = $this->getId();
                $additionalSettingsJs = showAvatars: . var_export($this->showAvatars, true) . ,;
                if ($this->classes)
                {
                    $additionalSettingsJs .=  $this->classes . ',';
                };
                if ($this->templates)
                {
                    $additionalSettingsJs .=  $this->templates;
                };


--------


.. _thinkphp-structures-emptylines:

ThinkPHP
^^^^^^^^

:ref:`empty-instructions`, in ThinkPHP/Library/Vendor/Smarty/sysplugins/smarty_internal_configfileparser.php:83. 

There is no need for a semi-colon after a class structure, unless it is an anonymous class.

.. code-block:: php

    class TPC_yyStackEntry
    {
        public $stateno;       /* The state-number */
        public $major;         /* The major token value.  This is the code
                         ** number for the token at this stack level */
        public $minor; /* The user-supplied minor token value.  This
                         ** is the value of the token  */
    };

Should Use Constants
====================

.. _tine20-functions-shoulduseconstants:

Tine20
^^^^^^

:ref:`should-use-constants`, in tine20/Sales/Controller/Invoice.php:560. 

True should be replaced by COUNT_RECURSIVE. The default one is COUNT_NORMAL.

.. code-block:: php

    count($billables, true)

No Parenthesis For Language Construct
=====================================

.. _phpdocumentor-structures-noparenthesisforlanguageconstruct:

Phpdocumentor
^^^^^^^^^^^^^

:ref:`no-parenthesis-for-language-construct`, in src/Application/Renderer/Router/StandardRouter.php:55. 

No need for parenthesis with require(). instanceof has a higher precedence than return anyway. 

.. code-block:: php

    $this[] = new Rule(function ($node) { return ($node instanceof NamespaceDescriptor); }, $namespaceGenerator);


--------


.. _phpmyadmin-structures-noparenthesisforlanguageconstruct:

phpMyAdmin
^^^^^^^^^^

:ref:`no-parenthesis-for-language-construct`, in db_datadict.php:170. 

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

Assign Default To Properties
============================

.. _livezilla-classes-makedefault:

LiveZilla
^^^^^^^^^

:ref:`assign-default-to-properties`, in livezilla/_lib/functions.external.inc.php:174. 

Flags may default to array() in the class definition. Filled array(), with keys and values, are also possible. 

.. code-block:: php

    class OverlayChat
    {
        public $Botmode;
        public $Human;
        public $HumanGeneral;
        public $RepollRequired;
        public $OperatorCount;
        public $Flags;
        public $LastMessageReceived;
        public $LastPostReceived;
        public $IsHumanChatAvailable;
        public $IsChatAvailable;
        public $ChatHTML;
        public $OverlayHTML;
        public $PostHTML;
        public $FullLoad;
        public $LanguageRequired = false;
        public $LastPoster;
        public $EyeCatcher;
        public $GroupBuilder;
        public $CurrentOperatorId;
        public $BotTitle;
        public $OperatorPostCount;
        public $PlaySound;
        public $SpeakingToHTML;
        public $SpeakingToAdded;
        public $Version = 1;
    
        public static $MaxPosts = 50;
        public static $Response;
    
        function __construct()
        {
            $this->Flags = array();
            VisitorChat::$Router = new ChatRouter();
        }


--------


.. _phpmyadmin-classes-makedefault:

phpMyAdmin
^^^^^^^^^^

:ref:`assign-default-to-properties`, in libraries/classes/Console.ph:55. 

_isEnabled may default to true. It could also default to a class constant.

.. code-block:: php

    class Console
    {
        /**
         * Whether to display anything
         *
         * @access private
         * @var bool
         */
        private $_isEnabled;
    
    // some code ignored here
        /**
         * Creates a new class instance
         */
        public function __construct()
        {
            $this->_isEnabled = true;

Echo With Concat
================

.. _phpdocumentor-structures-echowithconcat:

Phpdocumentor
^^^^^^^^^^^^^

:ref:`echo-with-concat`, in src/phpDocumentor/Bootstrap.php:76. 

Simply replace the dot by a comma.

.. code-block:: php

    echo 'PROFILING ENABLED' . PHP_EOL


--------


.. _teampass-structures-echowithconcat:

TeamPass
^^^^^^^^

:ref:`echo-with-concat`, in includes/libraries/Authentication/Yubico/PEAR.php:162. 

This is less obvious, but turning print to echo, and the double-quoted string to single quoted string will yield the same optimisation.

.. code-block:: php

    print "PEAR constructor called, class=$classname\n";

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

Pre-increment
=============

.. _expressionengine-performances-prepostincrement:

ExpressionEngine
^^^^^^^^^^^^^^^^

:ref:`pre-increment`, in system/ee/EllisLab/ExpressionEngine/Controller/Utilities/Communicate.php:650. 

Using preincrement in for() loops is safe and straightforward. 

.. code-block:: php

    for ($x = 0; $x < $number_to_send; $x++)
    		{
    			$email_address = array_shift($recipient_array);
    
    			if ( ! $this->deliverEmail($email, $email_address))
    			{
    				$email->delete();
    
    				$debug_msg = ee()->email->print_debugger(array());
    
    				show_error(lang('error_sending_email').BR.BR.$debug_msg);
    			}
    			$email->total_sent++;
    		}


--------


.. _traq-performances-prepostincrement:

Traq
^^^^

:ref:`pre-increment`, in src/Controllers/Tickets.php:84. 

$this->currentProject->next_ticket_id value is ignored by the code. It may be turned into a preincrement.

.. code-block:: php

    TimelineModel::newTicketEvent($this->currentUser, $ticket)->save();
    
                $this->currentProject->next_ticket_id++;
                $this->currentProject->save();

Should Typecast
===============

.. _xataface-type-shouldtypecast:

xataface
^^^^^^^^

:ref:`should-typecast`, in Dataface/Relationship.php:1612. 

This is an exact example. A little further, the same applies to intval($max)) 

.. code-block:: php

    intval($min);


--------


.. _openconf-type-shouldtypecast:

OpenConf
^^^^^^^^

:ref:`should-typecast`, in author/upload.php:62. 

This is another exact example. 

.. code-block:: php

    intval($_POST['pid']);

No Direct Usage
===============

.. _edusoho-structures-nodirectusage:

Edusoho
^^^^^^^

:ref:`no-direct-usage`, in edusoho/src/AppBundle/Controller/Admin/FinanceSettingController.php:107. 

Glob() returns false, in case of error. It returns an empty array in case everything is fine, but nothing was found. In case of error, array_map() will stop the script.

.. code-block:: php

    array_map('unlink', glob($dir.'/MP_verify_*.txt'));


--------


.. _xoops-structures-nodirectusage:

XOOPS
^^^^^

:ref:`no-direct-usage`, in htdocs/Frameworks/moduleclasses/moduleadmin/moduleadmin.php:585. 

Although the file is readable, file() may return false in case of failure. On the other hand, implode doesn't accept boolean values.

.. code-block:: php

    $file = XOOPS_ROOT_PATH . /modules/{$module_dir}/docs/changelog.txt;
                if ( is_readable( $file ) ) {
                    $ret .= implode( '<br>', file( $file ) ) . \n;
                }

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

Silently Cast Integer
=====================

.. _mediawiki-type-silentlycastinteger:

MediaWiki
^^^^^^^^^

:ref:`silently-cast-integer`, in includes/debug/logger/monolog/AvroFormatter.php:167. 

Too many ff in the masks. 

.. code-block:: php

    private function encodeLong( $id ) {
    		$high   = ( $id & 0xffffffff00000000 ) >> 32;
    		$low    = $id & 0x00000000ffffffff;
    		return pack( 'NN', $high, $low );
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

Unused Arguments
================

.. _thinkphp-functions-unusedarguments:

ThinkPHP
^^^^^^^^

:ref:`unused-arguments`, in ThinkPHP/Library/Behavior/AgentCheckBehavior.class.php:18. 

$params are requested, but never used. The method is not overloading another one, as the class doesn't extends anything. $params is unused. 

.. code-block:: php

    class AgentCheckBehavior
    {
        public function run(&$params)
        {
            // 代理访问检测
            $limitProxyVisit = C('LIMIT_PROXY_VISIT', null, true);
            if ($limitProxyVisit && ($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_PROXY_CONNECTION'] || $_SERVER['HTTP_USER_AGENT_VIA'])) {
                // 禁止代理访问
                exit('Access Denied');
            }
        }
    }


--------


.. _phpmyadmin-functions-unusedarguments:

phpMyAdmin
^^^^^^^^^^

:ref:`unused-arguments`, in libraries/classes/Display/Results.php:1985. 

Although $column_index is documented, it is not found in the rest of the (long) body of the function. It might have been refactored into $sorted_column_index.

.. code-block:: php

    /**
         * Prepare parameters and html for sorted table header fields
         *
         * @param array    $sort_expression             sort expression
         * @param array    $sort_expression_nodirection sort expression without direction
         * @param string   $sort_tbl                    The name of the table to which
         *                                             the current column belongs to
         * @param string   $name_to_use_in_sort         The current column under
         *                                             consideration
         * @param array    $sort_direction              sort direction
         * @param stdClass $fields_meta                 set of field properties
         * @param integer  $column_index                The index number to current column
         *
         * @return  array   3 element array - $single_sort_order, $sort_order, $order_img
         *
         * @access  private
         *
         * @see     _getOrderLinkAndSortedHeaderHtml()
         */
        private function _getSingleAndMultiSortUrls(
            array $sort_expression,
            array $sort_expression_nodirection,
            $sort_tbl,
            $name_to_use_in_sort,
            array $sort_direction,
            $fields_meta,
            $column_index
        ) {
        /**/
            // find the sorted column index in row result
            // (this might be a multi-table query)
            $sorted_column_index = false;
        /**/
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

No Hardcoded Hash
=================

.. _shopware-structures-nohardcodedhash:

shopware
^^^^^^^^

:ref:`no-hardcoded-hash`, in engine/Shopware/Models/Document/Data/OrderData.php:254. 

This is actually a hashed hardcoded password. As the file explains, this is a demo order, for populating the database when in demo mode, so this is fine. We also learn that the password are securily sorted here. It may also be advised to avoid hardcoding this password, as any demo shop has the same user credential : it is the first to be tried when a demo installation is found. 

.. code-block:: php

    '_userID' => '3',
        '_user' => new ArrayObject([
                'id' => '3',
                'password' => '$2y$10$GAGAC6.1kMRvN4RRcLrYleDx.EfWhHcW./cmoOQg11sjFUY73SO.C',
                'encoder' => 'bcrypt',
                'email' => 'demo@shopware.com',
                'customernumber' => '20005',


--------


.. _sugarcrm-structures-nohardcodedhash:

SugarCrm
^^^^^^^^

:ref:`no-hardcoded-hash`, in SugarCE-Full-6.5.26/include/Smarty/Smarty.class.php:460. 

The MD5('Smarty') is hardcoded in the properties. This property is not used in the class, but in parts of the code, when a unique delimiter is needed. 

.. code-block:: php

    /**
         * md5 checksum of the string 'Smarty'
         *
         * @var string
         */
        var $_smarty_md5           = 'f8d698aea36fcbead2b9d5359ffca76f';

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

Useless Switch
==============

.. _phpdocumentor-structures-uselessswitch:

Phpdocumentor
^^^^^^^^^^^^^

:ref:`useless-switch`, in fuel/modules/fuel/libraries/Inspection.php:349. 

This method parses comments. In fact, comments are represented by other tokens, which may be added or removed at time while coding.

.. code-block:: php

    public function parse_comments($code)
    	{
    		$comments = array();
    		$tokens = token_get_all($code);
    		
    		foreach($tokens as $token)
    		{
    			switch($token[0])
    			{
    				case T_DOC_COMMENT:
    					$comments[] = $token[1];
    					break;
    		    }
    		}
    		return $comments;
    		
    	}


--------


.. _dolphin-structures-uselessswitch:

Dolphin
^^^^^^^

:ref:`useless-switch`, in Dolphin-v.7.3.5/inc/classes/BxDolModuleDb.php:34. 

$aParams is an argument : this code looks like the switch is reserved for future use.

.. code-block:: php

    function getModulesBy($aParams = array())
    	{
    		$sMethod = 'getAll';
            $sPostfix = $sWhereClause = "";
    
            $sOrderClause = "ORDER BY `title`";
            switch($aParams['type']) {
                case 'path':
                	$sMethod = 'getRow';
                    $sPostfix .= '_path';
                    $sWhereClause .= "AND `path`='" . $aParams['value'] . "'";
                    break;
            }

Could Use __DIR__
=================

.. _woocommerce-structures-couldusedir:

Woocommerce
^^^^^^^^^^^

:ref:`could-use-\_\_dir\_\_`, in includes/class-wc-api.php:162. 

All the 120 occurrences use `dirname( __FILE__ )`, and could be upgraded to __DIR__ if backward compatibility to PHP 5.2 is not critical. 

.. code-block:: php

    private function rest_api_includes() {
    		// Exception handler.
    		include_once dirname( __FILE__ ) . '/api/class-wc-rest-exception.php';
    
    		// Authentication.
    		include_once dirname( __FILE__ ) . '/api/class-wc-rest-authentication.php';


--------


.. _piwigo-structures-couldusedir:

Piwigo
^^^^^^

:ref:`could-use-\_\_dir\_\_`, in include/random_compat/random.php:50. 

`dirname( __FILE__ )` is cached into $RandomCompatDIR, then reused three times. Using __DIR__ would save that detour.

.. code-block:: php

    $RandomCompatDIR = dirname(__FILE__);
    
        require_once $RandomCompatDIR.'/byte_safe_strings.php';
        require_once $RandomCompatDIR.'/cast_to_int.php';
        require_once $RandomCompatDIR.'/error_polyfill.php';

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

Always Positive Comparison
==========================

.. _magento-structures-nevernegative:

Magento
^^^^^^^

:ref:`always-positive-comparison`, in app/code/core/Mage/Dataflow/Model/Profile.php:85. 

strlen(($actiosXML) will never be negative, and hence, is always false. This exception is never thrown. 

.. code-block:: php

    if (strlen($actionsXML) < 0 &&
            @simplexml_load_string('<data>' . $actionsXML . '</data>', null, LIBXML_NOERROR) === false) {
                Mage::throwException(Mage::helper('dataflow')->__("Actions XML is not valid."));
            }

Empty Blocks
============

.. _cleverstyle-structures-emptyblocks:

Cleverstyle
^^^^^^^^^^^

:ref:`empty-blocks`, in modules/Blogs/api/Controller.php:44. 

Else is empty, but commented. 

.. code-block:: php

    public static function posts_get ($Request) {
    		$id = $Request->route_ids(0);
    		if ($id) {
    			$post = Posts::instance()->get($id);
    			if (!$post) {
    				throw new ExitException(404);
    			}
    			return $post;
    		} else {
    			// TODO: implement latest posts
    		}
    	}


--------


.. _phpipam-structures-emptyblocks:

PhpIPAM
^^^^^^^

:ref:`empty-blocks`, in wp-admin/includes/misc.php:74. 

The ``then`` block is empty and commented : yet, it may have been clearer to make the condition != and omitted the whole empty block.

.. code-block:: php

    /* checks */
    if($_POST['action'] == delete) {
    	# no cecks
    }
    else {
    	# remove spaces
    	$_POST['name'] = trim($_POST['name']);
    
    	# length > 4 and < 12
    	if( (mb_strlen($_POST['name']) < 2) || (mb_strlen($_POST['name']) > 24) ) 	{ $errors[] = _('Name must be between 4 and 24 characters'); }

Hidden Use Expression
=====================

.. _tikiwiki-namespaces-hiddenuse:

Tikiwiki
^^^^^^^^

:ref:`hidden-use-expression`, in /lib/core/Tiki/Command/DailyReportSendCommand.php:17. 

Sneaky error_reporting, hidden among the use calls. 

.. code-block:: php

    namespace Tiki\Command;
    
    use Symfony\Component\Console\Command\Command;
    use Symfony\Component\Console\Input\InputArgument;
    use Symfony\Component\Console\Input\InputInterface;
    use Symfony\Component\Console\Input\InputOption;
    use Symfony\Component\Console\Output\OutputInterface;
    error_reporting(E_ALL);
    use TikiLib;
    use Reports_Factory;


--------


.. _openemr-namespaces-hiddenuse:

OpenEMR
^^^^^^^

:ref:`hidden-use-expression`, in interface/patient_file/summary/browse.php:23. 

Use expression is only reached when the csrf token is checked. This probably save some CPU when no csrf is available, but it breaks the readability of the file.

.. code-block:: php

    <?php
    /**
     * Patient selector for insurance gui
     *
     * @package   OpenEMR
     * @link      http://www.open-emr.org
     * @author    Brady Miller <brady.g.miller@gmail.com>
     * @copyright Copyright (c) 2018 Brady Miller <brady.g.miller@gmail.com>
     * @license   https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
     */
    
    
    require_once(../../globals.php);
    require_once($srcdir/patient.inc);
    require_once($srcdir/options.inc.php);
    
    if (!empty($_POST)) {
        if (!verifyCsrfToken($_POST[csrf_token_form])) {
            csrfNotVerified();
        }
    }
    
    use OpenEMR\Core\Header;

Multiple Alias Definitions
==========================

.. _churchcrm-namespaces-multiplealiasdefinitions:

ChurchCRM
^^^^^^^^^

:ref:`multiple-alias-definitions`, in Various files:--. 

It is actually surprising to find FamilyQuery defined as ChurchCRM\Base\FamilyQuery only once, while all other reference are for ChurchCRM\FamilyQuery. That lone use is actually useful in the code, so it is not a forgotten refactorisation. 

.. code-block:: php

    use ChurchCRM\Base\FamilyQuery	// in /src/MapUsingGoogle.php:7
    
    use ChurchCRM\FamilyQuery	// in /src/ChurchCRM/Dashboard/EventsDashboardItem.php:8
                                // and 29 other files


--------


.. _phinx-namespaces-multiplealiasdefinitions:

Phinx
^^^^^

:ref:`multiple-alias-definitions`, in Various files:--. 

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

Use Positive Condition
======================

.. _spip-structures-usepositivecondition:

SPIP
^^^^

:ref:`use-positive-condition`, in ecrire/inc/utils.php:925. 

if (isset($time[$t])) { } else { } would put the important case in first place, and be more readable.

.. code-block:: php

    if (!isset($time[$t])) {
    		$time[$t] = $a + $b;
    	} else {
    		$p = ($a + $b - $time[$t]) * 1000;
    		unset($time[$t]);
    #			echo "'$p'";exit;
    		if ($raw) {
    			return $p;
    		}
    		if ($p < 1000) {
    			$s = '';
    		} else {
    			$s = sprintf("%d ", $x = floor($p / 1000));
    			$p -= ($x * 1000);
    		}
    
    		return $s . sprintf($s ? "%07.3f ms" : "%.3f ms", $p);
    	}


--------


.. _expressionengine-structures-usepositivecondition:

ExpressionEngine
^^^^^^^^^^^^^^^^

:ref:`use-positive-condition`, in system/ee/EllisLab/Addons/forum/mod.forum_core.php:9138. 

Let's be positive, and start processing the presence of $topic first. And let's call it empty(),  not == ''.

.. code-block:: php

    if ($topic != '')
    						{
    							$sql .= '('.substr($topic, 0, -3).') OR ';
    							$sql .= '('.substr($tbody, 0, -3).') ';
    						}
    						else
    						{
    							$sql = substr($sql, 0, -3);
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

:ref:`no-isset()-with-empty()`, in htdocs/class/tree.php:297. 

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

:ref:`illegal-name-for-method`, in admin-dev/ajaxfilemanager/inc/class.pagination.php:200. 

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

Long Arguments
==============

.. _cleverstyle-structures-longarguments:

Cleverstyle
^^^^^^^^^^^

:ref:`long-arguments`, in core/drivers/DB/MySQLi.php:40. 

This query is not complex, but its length tend to push the end out of the view in the IDE. It could be rewritten as a variable, on the previous line, with some formatting. The same formatting would help without the variable too, yet, mixing the SQL syntax with the PHP methodcall adds a layer of confusion. 

.. code-block:: php

    $this->instance->query("SET SESSION sql_mode='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION'")


--------


.. _contao-structures-longarguments:

Contao
^^^^^^

:ref:`long-arguments`, in core-bundle/src/Resources/contao/widgets/CheckBoxWizard.php:145. 

This one-liner includes 9 members and 6 variables : some are formatted by sprintf, some are directly concatenated in the string. Breaking this into two lines improves readbility and code review.

.. code-block:: php

    sprintf('<span><input type="checkbox" name="%s" id="opt_%s" class="tl_checkbox" value="%s"%s%s onfocus="Backend.getScrollOffset()"> %s<label for="opt_%s">%s</label></span>', $this->strName . ($this->multiple ? '[]' : ''), $this->strId . '_' . $i, ($this->multiple ? \StringUtil::specialchars($arrOption['value']) : 1), (((\is_array($this->varValue) && \in_array($arrOption['value'], $this->varValue)) || $this->varValue == $arrOption['value']) ? ' checked="checked"' : ''), $this->getAttributes( ), $strButtons, $this->strId . '_' . $i, $arrOption['label'])

No Boolean As Default
=====================

.. _openconf-functions-nobooleanasdefault:

OpenConf
^^^^^^^^

:ref:`no-boolean-as-default`, in openconf/include.php:1264. 

Why do we need a `chair` when printing a cell's file ? 

.. code-block:: php

    function oc_printFileCells(&$sub, $chair = false) { /**/ }

Strange Name For Variables
==========================

.. _fuelcms-variables-strangename:

FuelCMS
^^^^^^^

:ref:`strange-name-for-variables`, in fuel/modules/fuel/libraries/parser/dwoo/Dwoo/Adapters/CakePHP/dwoo.php:86. 

Three _ is quite a lot for variables. Would they not be parameters but global variables, that would still be quite a lot.

.. code-block:: php

    public function _render($___viewFn, $___data_for_view, $___play_safe = true, $loadHelpers = true) {
        /**/
    }


--------


.. _phpipam-variables-strangename:

PhpIPAM
^^^^^^^

:ref:`strange-name-for-variables`, in app/admin/sections/edit-result.php:56. 

$sss is the end-result of a progression, from $subsections (3s) to $ss to $sss. Although it is understandable from the code, a fuller name, like $subsection_subnet or $one_subsection_subnet would make this more readable.

.. code-block:: php

    //fetch subsection subnets
    		foreach($subsections as $ss) {
    			$subsection_subnets = $Subnets->fetch_section_subnets($ss->id);	//fetch all subnets in subsection
    			if(sizeof($subsection_subnets)>0) {
    				foreach($subsection_subnets as $sss) {
    					$out[] = $sss;
    				}
    			}
    			$num_subnets = $num_subnets + sizeof($subsection_subnets);
    			//count all addresses that will be deleted!
    			$ipcnt = $Addresses->count_addresses_in_multiple_subnets($out);
    		}

Check All Types
===============

.. _zend-config-structures-checkalltypes:

Zend-Config
^^^^^^^^^^^

:ref:`check-all-types`, in src/Writer/Ini.php:122. 

$value must be an array or a string here. 

.. code-block:: php

    foreach ($config as $key => $value) {
                $group = array_merge($parents, [$key]);
    
                if (is_array($value)) {
                    $iniString .= $this->addBranch($value, $group);
                } else {
                    $iniString .= implode($this->nestSeparator, $group)
                               .  ' = '
                               .  $this->prepareValue($value)
                               .  \n;
                }
            }


--------


.. _vanilla-structures-checkalltypes:

Vanilla
^^^^^^^

:ref:`check-all-types`, in src/Writer/Ini.php:122. 

When $this->_FormValues is not null, then it is an array or an object, as it may be used immediately with foreach(). A check with is_array() would be a stronger option here.

.. code-block:: php

    if (is_null($this->_FormValues)) {
                $this->formValues();
            }
    
            $result = [[]];
            foreach ($this->_FormValues as $key => $value) {
                if (is_array($value)) {

Missing Cases In Switch
=======================

.. _tikiwiki-structures-missingcases:

Tikiwiki
^^^^^^^^

:ref:`missing-cases-in-switch`, in lib/articles/artlib.php:1075. 

This switch handles 3 cases, plus the default for all others. There are other switch structures which also handle the '' case. There may be a missing case here. In particular, projects/tikiwiki/code//article_image.php host another switch with the same case, plus another 'topic' case.

.. code-block:: php

    switch ($image_type) {
    			case 'article':
    				$image_cache_prefix = 'article';
    				break;
    			case 'submission':
    				$image_cache_prefix = 'article_submission';
    				break;
    			case 'preview':
    				$image_cache_prefix = 'article_preview';
    				break;
    			default:
    				return false;
    		}

No Class In Global
==================

.. _dolphin-php-noclassinglobal:

Dolphin
^^^^^^^

:ref:`no-class-in-global`, in Dolphin-v.7.3.5/inc/classes/BxDolXml.php:10. 

This class should be put away in a 'dolphin' or 'boonex' namespace.

.. code-block:: php

    class BxDolXml { 
        /* class BxDolXML code */ 
    }

Could Use str_repeat()
======================

.. _zencart-structures-couldusestrrepeat:

Zencart
^^^^^^^

:ref:`could-use-str\_repeat()`, in includes/functions/functions_general.php:1234. 

That's a 45 repeat of &nbsp;

.. code-block:: php

    if ( (!zen_browser_detect('MSIE')) && (zen_browser_detect('Mozilla/4')) ) {
          for ($i=0; $i<45; $i++) $pre .= '&nbsp;';
        }


--------


.. _zencart-structures-couldusestrrepeat:

Zencart
^^^^^^^

:ref:`could-use-str\_repeat()`, in includes/functions/functions_general.php:1234. 

That's a $len repeat of &nbsp;. Also, the first $len is useless.

.. code-block:: php

    for ($len; $len > 0; $len--) {
                $str .= '.0';
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

Strings With Strange Space
==========================

.. _openemr-type-stringwithstrangespace:

OpenEMR
^^^^^^^

:ref:`strings-with-strange-space`, in library/globals.inc.php:3270. 

The name of the contry contains both an unsecable space (the first, after Tonga), and a normal space (between Tonga and Islands). Translations are stored in a database, which preserves the unbreakable spaces. This also means that fixing the translation must be applied to every piece of data at the same time. The xl() function, which handles the translations, is also a good place to clean the spaces before searching for the right translation.

.. code-block:: php

    'to' => xl('Tonga (Tonga Islands)'),


--------


.. _thelia-type-stringwithstrangespace:

Thelia
^^^^^^

:ref:`strings-with-strange-space`, in templates/backOffice/default/I18n/fr_FR.php:647. 

This is another example with a translation sentence. Here, the unbreakable space is before the question mark : this is a typography rule, that is common to many language. This would be a false positive, unless typography is handled by another part of the software.

.. code-block:: php

    'Mot de passe oublié ?'

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

No Class As Typehint
====================

.. _vanilla-functions-noclassastypehint:

Vanilla
^^^^^^^

:ref:`no-class-as-typehint`, in library/Vanilla/Formatting/Formats/RichFormat.php:51. 

All three typehints are based on classes. When Parser or Renderer are changed, for testing, versioning or moduling reasons, they must subclass the original class. 

.. code-block:: php

    public function __construct(Quill\Parser $parser, Quill\Renderer $renderer, Quill\Filterer $filterer) {
            $this->parser = $parser;
            $this->renderer = $renderer;
            $this->filterer = $filterer;
        }


--------


.. _phpmyadmin-functions-noclassastypehint:

phpMyAdmin
^^^^^^^^^^

:ref:`no-class-as-typehint`, in libraries/classes/CreateAddField.php:29. 

Although the class is named 'DatabaseInterface', it is a class.

.. code-block:: php

    public function __construct(DatabaseInterface $dbi)
        {
            $this->dbi = $dbi;
        }

No Return Used
==============

.. _spip-functions-noreturnused:

SPIP
^^^^

:ref:`no-return-used`, in ecrire/inc/utils.php:1067. 

job_queue_remove() is called as an administration order, and the result is not checked. It is considered as a fire-and-forget command. 

.. code-block:: php

    function job_queue_remove($id_job) {
    	include_spip('inc/queue');
    
    	return queue_remove_job($id_job);
    }


--------


.. _livezilla-functions-noreturnused:

LiveZilla
^^^^^^^^^

:ref:`no-return-used`, in livezilla/_lib/trdp/Zend/Loader.php:114. 

The loadFile method tries to load a file, aka as include. If the inclusion fails, a PHP error is emitted (an exception would do the same), and there is not error management. Hence, the 'return true;', which is not tested later. It may be dropped.

.. code-block:: php

    public static function loadFile($filename, $dirs = null, $once = false)
        {
    // A lot of code to check and include files
    
            return true;
        }

Mixed Concat And Interpolation
==============================

.. _suitecrm-structures-mixedconcatinterpolation:

SuiteCrm
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

@ Operator
==========

.. _phinx-structures-noscream:

Phinx
^^^^^

:ref:`@-operator`, in src/Phinx/Util/Util.php:239. 

fopen() may be tested for existence, readability before using it. Although, it actually emits some errors on Windows, with network volumes.

.. code-block:: php

    $isReadable = @\fopen($filePath, 'r') !== false;
    
            if (!$filePath || !$isReadable) {
                throw new \Exception(sprintf(Cannot open file %s \n, $filename));
            }


--------


.. _phpipam-structures-noscream:

PhpIPAM
^^^^^^^

:ref:`@-operator`, in functions/classes/class.Log.php:322. 

Variable and index existence should always be tested with isset() : it is faster than using ``@``.

.. code-block:: php

    $_SESSION['ipamusername']

Mismatched Ternary Alternatives
===============================

.. _phpadsnew-structures-mismatchedternary:

phpadsnew
^^^^^^^^^

:ref:`mismatched-ternary-alternatives`, in phpAdsNew-2.0/admin/lib-misc-stats.inc.php:219. 

This is an unusual way to apply a condition. $bgcolor is '#FFFFFF' by default, and if $i % 2, then $bcolor is '#F6F6F6';. A more readable ternary option would be '$bgcolor =  = 	$i % 2 ? "#FFFFFF" : "#F6F6F6";', and make a matched alternative branches.

.. code-block:: php

    $bgcolor = #FFFFFF;
    	$i % 2 ? 0 : $bgcolor = #F6F6F6;


--------


.. _openemr-structures-mismatchedternary:

OpenEMR
^^^^^^^

:ref:`mismatched-ternary-alternatives`, in portal/messaging/messages.php:132. 

IS_DASHBOARD is defined as a boolean or a string. Later, it is tested as a boolean, and displayed as a integer, which will be cast to string by echo. Lots of transtyping are happening here.

.. code-block:: php

    // In two distinct if/then branch
    l:29) define('IS_DASHBOARD', false);
    l:41) define('IS_DASHBOARD', $_SESSION['authUser']);
    
    l:132) echo IS_DASHBOARD ? IS_DASHBOARD : 0;
    ?>

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

SuiteCrm
^^^^^^^^

:ref:`is-actually-zero`, in modules/AOR_Charts/lib/pChart/class/pDraw.class.php:523. 

$Xa may only amount to $iX2, though the expression looks weird.

.. code-block:: php

    if ( $X > $iX2 ) { $Xa = $X-($X-$iX2); $Ya = $iY1+($X-$iX2); } else { $Xa = $X; $Ya = $iY1; }

Could Be Else
=============

.. _sugarcrm-structures-couldbeelse:

SugarCrm
^^^^^^^^

:ref:`could-be-else`, in SugarCE-Full-6.5.26/modules/Emails/ListViewGroup.php:79. 

The first condition makes different checks if 'query' is in $_REQUEST or not. The second only applies to $_REQUEST['query'], as there is no else. There is also no visible sign that the first condition may change $_REQUEST or not

.. code-block:: php

    if(!isset($_REQUEST['query'])){
    	//_pp('loading: '.$currentModule.'Group');
    	//_pp($current_user->user_preferences[$currentModule.'GroupQ']);
    	$storeQuery->loadQuery($currentModule.'Group');
    	$storeQuery->populateRequest();
    } else {
    	//_pp($current_user->user_preferences[$currentModule.'GroupQ']);
    	//_pp('saving: '.$currentModule.'Group');
    	$storeQuery->saveFromGet($currentModule.'Group');
    }
    
    if(isset($_REQUEST['query'])) {
    	// we have a query
    	if(isset($_REQUEST['email_type']))				$email_type = $_REQUEST['email_type'];
    	if(isset($_REQUEST['assigned_to']))				$assigned_to = $_REQUEST['assigned_to'];
    	if(isset($_REQUEST['status']))					$status = $_REQUEST['status'];
    	// More code
    }


--------


.. _openemr-structures-couldbeelse:

OpenEMR
^^^^^^^

:ref:`could-be-else`, in library/log.inc:653. 

Those two if structure may definitely merged into one single instruction.

.. code-block:: php

    $success = 1;
        $checksum = ;
        if ($outcome === false) {
            $success = 0;
        }
    
        if ($outcome !== false) {
            // Should use the $statement rather than the processed
            // variables, which includes the binded stuff. If do
            // indeed need the binded values, then will need
            // to include this as a separate array.
    
            //error_log(STATEMENT: .$statement,0);
            //error_log(BINDS: .$processed_binds,0);
            $checksum = sql_checksum_of_modified_row($statement);
            //error_log(CHECKSUM: .$checksum,0);
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

.. _shopware-classes-parentfirst:

shopware
^^^^^^^^

:ref:`parent-first`, in wp-admin/includes/misc.php:74. 

Here, the parent is called last. Givent that $title is defined in the same class, it seems that $name may be defined in the BaseContainer class. In fact, it is not, and BasecContainer and FieldSet are fairly independant classes. Thus, the parent::__construct call could be first here, though more as a coding convention.

.. code-block:: php

    /**
     * Class FieldSet
     */
    class FieldSet extends BaseContainer
    {
        /**
         * @var string
         */
        protected $title;
    
        /**
         * @param string $name
         * @param string $title
         */
        public function __construct($name, $title)
        {
            $this->title = $title;
            $this->name = $name;
            parent::__construct();
        }


--------


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


--------


.. _humo-gen-structures-identicalonbothsides:

HuMo-Gen
^^^^^^^^

:ref:`identical-on-both-sides`, in libraries/classes/DatabaseInterface.php:323. 

In that long logical expression, $personDb->pers_cal_date is tested twice

.. code-block:: php

    // *** Filter person's WITHOUT any date's ***
    			if ($user[group_filter_date]=='j'){
    				if ($personDb->pers_birth_date=='' AND $personDb->pers_bapt_date==''
    				AND $personDb->pers_death_date=='' AND $personDb->pers_buried_date==''
    				AND $personDb->pers_cal_date=='' AND $personDb->pers_cal_date==''
    				){
    					$privacy_person='';
    				}
    			}

No Reference For Ternary
========================

.. _phpadsnew-php-noreferenceforternary:

phpadsnew
^^^^^^^^^

:ref:`no-reference-for-ternary`, in lib/OA/Admin/Menu/Section.php334:334. 

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

Cant Instantiate Class
======================

.. _wordpress-classes-cantinstantiateclass:

WordPress
^^^^^^^^^

:ref:`cant-instantiate-class`, in wp-admin/includes/misc.php:74. 

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    $markerdata = explode( "\n", implode( '', file( $filename ) ) );

strpos() Too Much
=================

.. _wordpress-performances-strpostoomuch:

WordPress
^^^^^^^^^

:ref:`strpos()-too-much`, in core/traits/Request/Server.php:127. 

Instead of searching for ``HTTP_``, it is faster to compare the first 5 chars to the literal ``HTTP_``. In case of absence, this solution returns faster.

.. code-block:: php

    if (strpos($header, 'HTTP_') === 0) {
    				$header = substr($header, 5);
    			} elseif (strpos($header, 'CONTENT_') !== 0) {
    				continue;
    			}

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

Mismatch Type And Default
=========================

.. _wordpress-functions-mismatchtypeanddefault:

WordPress
^^^^^^^^^

:ref:`mismatch-type-and-default`, in wp-admin/includes/misc.php:74. 

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    $markerdata = explode( "\n", implode( '', file( $filename ) ) );

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

Abstract Or Implements
======================

.. _zurmo-classes-abstractorimplements:

Zurmo
^^^^^

:ref:`abstract-or-implements`, in app/protected/extensions/zurmoinc/framework/views/MassEditProgressView.php:30. 

The class MassEditProgressView extends ProgressView, which is an abstract class. That class defines one abstract method : abstract protected function headerLabelPrefixContent(). Yet, the class MassEditProgressView doesn't implements this method. This means that the class can't be instatiated, and indeed, it isn't. The class MassEditProgressView is subclassed, by the class MarketingListMembersMassSubscribeProgressView, which implements the method headerLabelPrefixContent(). As such, MassEditProgressView should be marked abstract, so as to prevent any instantiation attempt. 

.. code-block:: php

    class MassEditProgressView extends ProgressView { 
        /**/ 
    }

Incompatible Signature Methods
==============================

.. _suitecrm-classes-incompatiblesignature:

SuiteCrm
^^^^^^^^

:ref:`incompatible-signature-methods`, in modules/Home/Dashlets/RSSDashlet/RSSDashlet.php:138. 

The class in the RSSDashlet.php file has an 'array' typehint which is not in the parent Dashlet class. While both files compile separately, they yield a PHP warning when running : typehinting mismatch only yields a warning. 

.. code-block:: php

    // File /modules/Home/Dashlets/RSSDashlet/RSSDashlet.php
        public function saveOptions(
            array $req
            )
        {
    
    // File /include/Dashlets/Dashlets.php
        public function saveOptions( $req ) {

Ambiguous Visibilities
======================

.. _typo3-classes-ambiguousvisibilities:

Typo3
^^^^^

:ref:`ambiguous-visibilities`, in typo3/sysext/backend/Classes/Controller/NewRecordController.php:90. 

$allowedNewTables is declared once  protected and once public. $allowedNewTables is rare : 2 occurences. This may lead to confusion about access to this property.

.. code-block:: php

    class NewRecordController
    {
    /.. many lines../
        /**
         * @var array
         */
        protected $allowedNewTables;
        
    class DatabaseRecordList
    {
    /..../ 
        /**
         * Used to indicate which tables (values in the array) that can have a
         * create-new-record link. If the array is empty, all tables are allowed.
         *
         * @var string[]
         */
        public $allowedNewTables = [];

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

Method Could Be Static
======================

.. _fuelcms-classes-couldbestatic:

FuelCMS
^^^^^^^

:ref:`method-could-be-static`, in fuel/modules/fuel/models/Fuel_assets_model.php:240. 

This method makes no usage of $this : it only works on the incoming argument, $file. This may even be a function.

.. code-block:: php

    public function get_file($file)
    	{
    		// if no extension is provided, then we determine that it needs to be decoded
    		if (strpos($file, '.') === FALSE)
    		{
    			$file = uri_safe_decode($file);
    		}
    		return $file;
    	}


--------


.. _expressionengine-classes-couldbestatic:

ExpressionEngine
^^^^^^^^^^^^^^^^

:ref:`method-could-be-static`, in system/ee/legacy/libraries/Upload.ph:859. 

This method returns the list of mime type, by using a hidden global value : ee() is a functioncall that give access to the external storage of values.

.. code-block:: php

    /**
    	 * List of Mime Types
    	 *
    	 * This is a list of mime types.  We use it to validate
    	 * the allowed types set by the developer
    	 *
    	 * @param	string
    	 * @return	string
    	 */
    	public function mimes_types($mime)
    	{
    		ee()->load->library('mime_type');
    		return ee()->mime_type->isSafeForUpload($mime);
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

One Letter Functions
====================

.. _thinkphp-functions-oneletterfunctions:

ThinkPHP
^^^^^^^^

:ref:`one-letter-functions`, in ThinkPHP/Mode/Api/functions.php:859. 

There are also the functions C, E, G... The applications is written in a foreign language, which may be a base for non-significant function names.

.. code-block:: php

    function F($name, $value = '', $path = DATA_PATH)


--------


.. _cleverstyle-functions-oneletterfunctions:

Cleverstyle
^^^^^^^^^^^

:ref:`one-letter-functions`, in core/drivers/DB/PostgreSQL.php:71. 

There is also function f(). Those are actually overwritten methods. From the documentation, q() is for query, and f() is for fetch. Those are short names for frequently used functions.

.. code-block:: php

    public function q ($query, ...$params) {

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

.. _churchcrm-performances-slowfunctions:

ChurchCRM
^^^^^^^^^

:ref:`slow-functions`, in src/Reports/PrintDeposit.php:35. 

You may replace this with a isset() : $_POST can't contain a NULL value, unless it was set by the script itself.

.. code-block:: php

    array_key_exists("report_type", $_POST);


--------


.. _suitecrm-performances-slowfunctions:

SuiteCrm
^^^^^^^^

:ref:`slow-functions`, in include/json_config.php:242. 

This is a equivalent for nl2br()

.. code-block:: php

    preg_replace("/\r\n/", "<BR>", $focus->$field)

Joining file()
==============

.. _wordpress-performances-joinfile:

WordPress
^^^^^^^^^

:ref:`joining-file()`, in wp-admin/includes/misc.php:74. 

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    $markerdata = explode( "\n", implode( '', file( $filename ) ) );


--------


.. _spip-performances-joinfile:

SPIP
^^^^

:ref:`joining-file()`, in ecrire/inc/install.php:109. 

When the file is not accessible, file() returns null, and can't be processed by join(). 

.. code-block:: php

    $s = @join('', file($file));


--------


.. _expressionengine-performances-joinfile:

ExpressionEngine
^^^^^^^^^^^^^^^^

:ref:`joining-file()`, in ExpressionEngine_Core2.9.2/system/expressionengine/libraries/simplepie/idn/idna_convert.class.php:100. 

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

:ref:`joining-file()`, in classes/module/Module.php:2972. 

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

Substring First
===============

.. _spip-performances-substrfirst:

SPIP
^^^^

:ref:`substring-first`, in ecrire/inc/filtres.php:1694. 

The code first makes everything uppercase, including the leading and trailing spaces, and then, removes them : it would be best to swap those operations. Note that spip_substr() is not considered in this analysis, but with SPIP knowledge, it could be moved inside the calls. 

.. code-block:: php

    function filtre_initiale($nom) {
    	return spip_substr(trim(strtoupper(extraire_multi($nom))), 0, 1);
    }


--------


.. _prestashop-performances-substrfirst:

PrestaShop
^^^^^^^^^^

:ref:`substring-first`, in admin-dev/filemanager/include/utils.php:197. 

dirname() reduces the string (or at least, keeps it the same size), so it more efficient to have it first.

.. code-block:: php

    dirname(str_replace(' ', '~', $str))

Slice Arrays First
==================

.. _wordpress-arrays-slicefirst:

WordPress
^^^^^^^^^

:ref:`slice-arrays-first`, in modules/InboundEmail/InboundEmail.php:1080. 

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

Safe Curl Options
=================

.. _openconf-security-curloptions:

OpenConf
^^^^^^^^

:ref:`safe-curl-options`, in openconf/include.php:703. 

The function that holds that code is only used to call openconf.com, over http, while openconf.com is hosted on https, nowadays. This may be a sign of hard to access certificates.

.. code-block:: php

    $ch = curl_init();
    			curl_setopt($ch, CURLOPT_URL, $f);
    			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);       
    			curl_setopt($ch, CURLOPT_AUTOREFERER, true);       
    			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);       
    			curl_setopt($ch, CURLOPT_MAXREDIRS, 5);       
    			curl_setopt($ch, CURLOPT_HEADER, false);       
    			$s = curl_exec($ch);
    			curl_close($ch);
    			return($s);

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

Encoded Simple Letters
======================

.. _zurmo-security-encodedletters:

Zurmo
^^^^^

:ref:`encoded-simple-letters`, in yii/framework/web/CClientScript.php:783. 

This actually decodes into a copyright notice. 

'function cleanAndSanitizeScriptHeader(& $output)
                        {
                            $requiredOne = <span>Copyright &#169; Zurmo Inc., 2013. All rights reserved.;....'


.. code-block:: php

    eval(\x66\x75\x6e\x63\x74\x69\x6f\x6e\x20\x63\x6c\x65\x61\x6e\x41\x6e\x64\x53\x61\x6e\x69\x74\x69\x7a\x65\x53\x63\x72 .
         \x69\x70\x74\x48\x65\x61\x64\x65\x72\x28\x26\x20\x24\x6f\x75\x74\x70\x75\x74\x29\x0d\x0a\x20\x20\x20\x20\x20\x20 .
         \x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x7b\x0d\x0a\x20\x20\x20\x20\x20\x20\x20 .
         \x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x20\x24\x72\x65\x71\x75\x69\x72 .
         // several more lines like that

Mkdir Default
=============

.. _mautic-security-mkdirdefault:

Mautic
^^^^^^

:ref:`mkdir-default`, in app/bundles/CoreBundle/Helper/AssetGenerationHelper.php:120. 

This code is creating some directories for Javascript or CSS (from the directories names) : those require universal reading access, but probably no execution nor writing access. 0711 would be sufficient in this case.

.. code-block:: php

    //combine the files into their corresponding name and put in the root media folder
                    if ($env == 'prod') {
                        $checkPaths = [
                            $assetsFullPath,
                            $assetsFullPath/css,
                            $assetsFullPath/js,
                        ];
                        array_walk($checkPaths, function ($path) {
                            if (!file_exists($path)) {
                                mkdir($path);
                            }
                        });


--------


.. _openemr-security-mkdirdefault:

OpenEMR
^^^^^^^

:ref:`mkdir-default`, in interface/main/backuplog.php:27. 

If $BACKUP_EVENTLOG_DIR is a backup for an event log, this should be stored out of the web server reach, with low rights, beside the current user. This is part of a CLI PHP script. 

.. code-block:: php

    mkdir($BACKUP_EVENTLOG_DIR)

Phpinfo
=======

.. _dolphin-structures-phpinfousage:

Dolphin
^^^^^^^

:ref:`phpinfo`, in Dolphin-v.7.3.5/install/exec.php:4. 

An actual phpinfo(), available during installation. Note that the phpinfo() is actually triggered by a hidden POST variable. 

.. code-block:: php

    <?php
    
        if (!empty($_POST['phpinfo']))
            phpinfo();
        elseif (!empty($_POST['gdinfo']))
            echo '<pre>' . print_r(gd_info(), true) . '</pre>';
    
    ?>
    <center>
    
        <form method=post>
            <input type=submit name=phpinfo value="PHP Info">
        </form>
        <form method=post>
            <input type=submit name=gdinfo value="GD Info">
        </form>
    
    </center>

Could Return Void
=================

.. _wordpress-functions-couldreturnvoid:

WordPress
^^^^^^^^^

:ref:`could-return-void`, in wp-admin/includes/misc.php:74. 

In fact, RunQuery returns a boolean, which should be also returned here. No Void needed if the last called method is not returning void too.

.. code-block:: php

    function RemoveVolunteerOpportunity($iPersonID, $iVolID)
    {
        $sSQL = 'DELETE FROM person2volunteeropp_p2vo WHERE p2vo_per_ID = '.$iPersonID.' AND p2vo_vol_ID = '.$iVolID;
        RunQuery($sSQL);
    }

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

Empty With Expression
=====================

.. _humo-gen-structures-emptywithexpression:

HuMo-Gen
^^^^^^^^

:ref:`empty-with-expression`, in fanchart.php:297. 

The test on $pid may be directly done on $treeid[$sosa][0]. The distance between the assignation and the empty() makes it hard to spot. 

.. code-block:: php

    $pid=$treeid[$sosa][0];
    			$birthyr=$treeid[$sosa][1];
    			$deathyr=$treeid[$sosa][4];
    			$fontpx=$fontsize;
    			if($sosa>=16 AND $fandeg==180) { $fontpx=$fontsize-1; }
    			if($sosa>=32 AND $fandeg!=180) { $fontpx=$fontsize-1; }
    			if (!empty($pid)) {

Should Use array_filter()
=========================

.. _xataface-php-shouldusearrayfilter:

xataface
^^^^^^^^

:ref:`should-use-array\_filter()`, in actions/manage_build_index.php:38. 

This selection process has three tests : the two first are exclusive, and the third is inclusive. They could fit in one or several closures.

.. code-block:: php

    $indexable = array();
    		foreach ( $tables as $key=>$table ){
    			if ( preg_match('/^dataface__/', $table) ){
    				continue;
    			}
    			if ( preg_match('/^_/', $table) ){
    				continue;
    			}
    			
    			if ( $index->isTableIndexable($table) ){
    				$indexable[] = $table;
    				//unset($tables[$key]);
    			}
    			
    		}


--------


.. _shopware-php-shouldusearrayfilter:

shopware
^^^^^^^^

:ref:`should-use-array\_filter()`, in engine/Shopware/Bundle/StoreFrontBundle/Service/Core/VariantCoverService.php:71. 

Closure would be the best here, since $covers has to be injected in the array_filter callback. 

.. code-block:: php

    $covers = $this->variantMediaGateway->getCovers(
                $products,
                $context
            );
    
            $fallback = [];
            foreach ($products as $product) {
                if (!array_key_exists($product->getNumber(), $covers)) {
                    $fallback[] = $product;
                }
            }

Could Use Compact
=================

.. _wordpress-structures-couldusecompact:

WordPress
^^^^^^^^^

:ref:`could-use-compact`, in wp-admin/includes/misc.php:74. 

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    $markerdata = explode( "\n", implode( '', file( $filename ) ) );

Could Use array_fill_keys
=========================

.. _churchcrm-structures-couldusearrayfillkeys:

ChurchCRM
^^^^^^^^^

:ref:`could-use-array\_fill\_keys`, in src/ManageEnvelopes.php:107. 

There are two initialisations at the same time here : that should make two call to array_fill_keys().

.. code-block:: php

    foreach ($familyArray as $fam_ID => $fam_Data) {
            $envelopesByFamID[$fam_ID] = 0;
            $envelopesToWrite[$fam_ID] = 0;
        }


--------


.. _phpipam-structures-couldusearrayfillkeys:

PhpIPAM
^^^^^^^

:ref:`could-use-array\_fill\_keys`, in functions/scripts/merge_databases.php:418. 

Even when the initialization is mixed with other operations, it is a good idea to extract it from the loop and give it to array_fill_keys(). 

.. code-block:: php

    $arr_new = array();
    				foreach ($arr as $type=>$objects) {
    					$arr_new[$type] = array();
    					if(sizeof($objects)>0) {
    						foreach($objects as $ok=>$object) {
    							$arr_new[$type][] = $highest_ids_append[$type] + $object;
    						}
    					}
    				}

Use Count Recursive
===================

.. _wordpress-structures-usecountrecursive:

WordPress
^^^^^^^^^

:ref:`use-count-recursive`, in wp-admin/includes/misc.php:74. 

This code actually loads the file, join it, then split it again. file() would be sufficient. 

.. code-block:: php

    $markerdata = explode( "\n", implode( '', file( $filename ) ) );


--------


.. _prestashop-structures-usecountrecursive:

PrestaShop
^^^^^^^^^^

:ref:`use-count-recursive`, in controllers/admin/AdminSearchController.php:342. 

This could be improved with count() recursive and a array_filter call, to remove empty $list.

.. code-block:: php

    $nb_results = 0;
                foreach ($this->_list as $list) {
                    if ($list != false) {
                        $nb_results += count($list);
                    }
                }

Should Preprocess Chr
=====================

.. _phpadsnew-php-shouldpreprocess:

phpadsnew
^^^^^^^^^

:ref:`should-preprocess-chr`, in phpAdsNew-2.0/adview.php:302. 

Each call to chr() may be done before. First, chr() may be replace with the hexadecimal sequence "0x3B"; Secondly, 0x3b is a rather long replacement for a simple semi-colon. The whole pragraph could be stored in a separate file, for easier modifications. 

.. code-block:: php

    echo chr(0x47).chr(0x49).chr(0x46).chr(0x38).chr(0x39).chr(0x61).chr(0x01).chr(0x00).
    		     chr(0x01).chr(0x00).chr(0x80).chr(0x00).chr(0x00).chr(0x04).chr(0x02).chr(0x04).
    		 	 chr(0x00).chr(0x00).chr(0x00).chr(0x21).chr(0xF9).chr(0x04).chr(0x01).chr(0x00).
    		     chr(0x00).chr(0x00).chr(0x00).chr(0x2C).chr(0x00).chr(0x00).chr(0x00).chr(0x00).
    		     chr(0x01).chr(0x00).chr(0x01).chr(0x00).chr(0x00).chr(0x02).chr(0x02).chr(0x44).
    		     chr(0x01).chr(0x00).chr(0x3B);

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

:ref:`one-if-is-sufficient`, in lib/wiki-plugins/wikiplugin_trade.php:152. 

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

:ref:`could-use-array\_unique`, in htdocs/includes/restler/framework/Luracast/Restler/Format/XmlFormat.php:250. 

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

:ref:`should-use-operator`, in includes/modules/payment/paypal/paypal_curl.php:378. 

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

Could Be Static Closure
=======================

.. _piwigo-functions-couldbestaticclosure:

Piwigo
^^^^^^

:ref:`could-be-static-closure`, in include/ws_core.inc.php:620. 

The closure function($m) makes no usage of the current object : using static prevents $this to be forwarded with the closure.

.. code-block:: php

    /**
       * WS reflection method implementation: lists all available methods
       */
      static function ws_getMethodList($params, &$service)
      {
        $methods = array_filter($service->_methods,
          function($m) { return empty($m["options"]["hidden"]) || !$m["options"]["hidden"];} );
        return array('methods' => new PwgNamedArray( array_keys($methods),'method' ) );
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

Add Default Value
=================

.. _zurmo-functions-adddefaultvalue:

Zurmo
^^^^^

:ref:`add-default-value`, in wp-admin/includes/misc.php:74. 

Default values may be a literal (1, 'abc', ...), or a constant : global or class. Here, MissionsListConfigurationForm::LIST_TYPE_AVAILABLE may be used directly in the signature of the method

.. code-block:: php

    public function getMetadataFilteredByOption($option)
            {
                if ($option == null)
                {
                    $option = MissionsListConfigurationForm::LIST_TYPE_AVAILABLE;
                }


--------


.. _typo3-functions-adddefaultvalue:

Typo3
^^^^^

:ref:`add-default-value`, in wp-admin/includes/misc.php:74. 

$extension could get a default value to handle default situations : for example, a file is htm format by default, unless better known. Also, the if/then structure could get a 'else' clause, to handle unknown situations : those are situations where the extension is provided but not known, in particular when the icon is missing in the storage folder.

.. code-block:: php

    public function getIcon($extension)
        {
            if ($extension === 'htm') {
                $extension = 'html';
            } elseif ($extension === 'jpeg') {
                $extension = 'jpg';
            }
            return 'EXT:indexed_search/Resources/Public/Icons/FileTypes/' . $extension . '.gif';
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

Don't Loop On Yield
===================

.. _dolibarr-structures-dontlooponyield:

Dolibarr
^^^^^^^^

:ref:`don't-loop-on-yield`, in htdocs/includes/sabre/sabre/dav/lib/DAV/Server.php:912. 

Yield from is a straight replacement here.

.. code-block:: php

    if (($newDepth === self::DEPTH_INFINITY || $newDepth >= 1) && $childNode instanceof ICollection) {
                    foreach ($this->generatePathNodes($subPropFind) as $subItem) {
                        yield $subItem;
                    }
                }


--------


.. _tikiwiki-structures-dontlooponyield:

Tikiwiki
^^^^^^^^

:ref:`don't-loop-on-yield`, in htdocs/includes/sabre/sabre/dav/lib/DAV/Server.php:912. 

The replacement with ``yield from``is not straigthforward here. Yield is only called when $user hasn't been ``$done`` : this is a unicity check. So, the double loop may produce a fully merged array, that may be reduced further by array_unique(). The final array, then, can be used with yield from. 

.. code-block:: php

    $done = [];
    
    			foreach ($goal['eligible'] as $groupName) {
    				foreach ($userlib->get_group_users($groupName) as $user) {
    					if (! isset($done[$user])) {
    						yield ['user' => $user, 'group' => null];
    						$done[$user] = true;
    					}
    				}
    			}

Multiple Usage Of Same Trait
============================

.. _nextcloud-traits-multipleusage:

NextCloud
^^^^^^^^^

:ref:`multiple-usage-of-same-trait`, in build/integration/features/bootstrap/WebDav.php:41. 

WebDav uses Sharing, and Sharing uses Webdav. Once using the other is sufficient. 

.. code-block:: php

    trait WebDav { 
        use Sharing;
        
    }
    //Trait Sharing is in /build/integration/features/bootstrap/Sharing.php:36

Inconsistent Usage
==================

.. _wordpress-variables-inconsistentusage:

WordPress
^^^^^^^^^

:ref:`inconsistent-usage`, in wp-includes/IXR/class-IXR-client.php:86. 

$request is used successively as an object (IXR_Request), then as a string (The POST). Separatring both usage with different names will help readability.

.. code-block:: php

    $request = new IXR_Request($method, $args);
            $length = $request->getLength();
            $xml = $request->getXml();
            $r = "\r\n";
            $request  = "POST {$this->path} HTTP/1.0$r";

Function Subscripting, Old Style
================================

.. _openconf-structures-functionpresubscripting:

OpenConf
^^^^^^^^

:ref:`function-subscripting,-old-style`, in openconf/include.php:1469. 

Here, $advocateid may be directly read from ocsql_fetch_assoc(), although, checking for the existence of 'advocateid' before accessing it would make the code more robust

.. code-block:: php

    $advocateid = false;
    	if (isset($GLOBALS['OC_configAR']['OC_paperAdvocates']) && $GLOBALS['OC_configAR']['OC_paperAdvocates']) {
    		$ar = ocsql_query(SELECT `advocateid` FROM ` . OCC_TABLE_PAPERADVOCATE . ` WHERE `paperid`=' . safeSQLstr($pid) . ') or err('Unable to retrieve advocate');
    		if (ocsql_num_rows($ar) == 1) {
    			$al = ocsql_fetch_assoc($ar);
    			$advocateid = $al['advocateid'];
    		}
    	}


