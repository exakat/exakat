<?php
/**
 * File containing the AppCache class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 * @version //autogentag//
 */
use eZ\Bundle\EzPublishCoreBundle\HttpCache;
/**
 * Class AppCache.
 *
 * For easier upgrade do not change this file, as of 2015.01 possible to extend
 * cleanly via SYMFONY_HTTP_CACHE_CLASS & SYMFONY_CLASSLOADER_FILE env variables!
 */
class AppCache extends HttpCache
{
}

ez();