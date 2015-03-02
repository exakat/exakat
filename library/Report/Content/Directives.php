<?php
/*
 * Copyright 2012-2015 Damien Seguy – Exakat Ltd <contact(at)exakat.io>
 * This file is part of Exakat.
 *
 * Exakat is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Exakat is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Exakat.  If not, see <http://www.gnu.org/licenses/>.
 *
 * The latest code can be found at <http://exakat.io/>.
 *
*/


namespace Report\Content;

class Directives extends \Report\Content {
    protected $name = 'Directives';
    
    public function collect() {
        $this->array['Standard'] = array();
        $this->array['Standard'][] = array('name' => 'memory_limit',
                                           'suggested' => '120',
                                           'documentation' => 'This sets the maximum amount of memory in bytes that a script is allowed to allocate. This helps prevent poorly written scripts for eating up all available memory on a server. It is recommended to set this as low as possible and avoid removing the limit.');

        $this->array['Standard'][] = array('name' => 'expose_php',
                                           'suggested' => 'Off',
                                           'documentation' => 'Exposes to the world that PHP is installed on the server. For security reasons, it is better to keep this hidden.');

        $this->array['Standard'][] = array('name' => 'date.timezone',
                                           'suggested' => 'Europe/Amsterdam',
                                           'documentation' => 'It is not safe to rely on the system\'s timezone settings. Make sure the directive date.timezone is set in php.ini.');

        // Filesystem
        $suggestion = $this->checkPresence('Php\\FileUsage');
        if ($suggestion == 'On') {
            $this->array['Filesystem'] = array();
            $this->array['Filesystem'][]  = array('name' => 'allow_url_fopen',
                                                  'suggested' => 'Off',
                                                  'documentation' => 'Unless you need to access remote files, it is better to be safe and forbid this feature');
        }

        // Assertions
        $suggestion = $this->checkPresence('Php\\AssertionUsage');
        if ($suggestion == 'On') {
            $this->array['Assertions'] = array();
            $this->array['Assertions'][]  = array('name' => 'assert.active',
                                                  'suggested' => 'On',
                                                  'documentation' => 'In production, set this to Off to remove all assertions. During developement, set this to On to activate them.');
        }

        // Filter
        $suggestion = $this->checkPresence('Extensions\\Extfilter');
        if ($suggestion == 'On') {
            $this->array['Filter'] = array();
            $this->array['Filter'] = array();
            $this->array['Filter'][]  = array('name' => 'filter.default',
                                              'suggested' => 'unsafe_raw', 
                                              'documentation' => 'Filter all $_GET, $_POST, $_COOKIE, $_REQUEST and $_SERVER data by this filter. Original data can be accessed through filter_input().');

            $this->array['Filter'][]  = array('name' => 'filter.default_flags',
                                              'suggested' => 'FILTER_FLAG_NO_ENCODE_QUOTES', 
                                              'documentation' => 'Default flags to apply when the default filter is set. This is set to FILTER_FLAG_NO_ENCODE_QUOTES by default for backwards compatibility reasons.');
        }

        // PDO
        $suggestion = $this->checkPresence('Extensions\\Extpdo');
        if ($suggestion == 'On') {
            $this->array['PDO'] = array();
            $this->array['PDO'][]  = array('name' => 'pdo.dns.*',
                                           'suggested' => 'sqlite:/opt/databases/mydb.sq3', // found value in the code
                                           'documentation' => 'By putting aliases of URI in the php.ini, you won\'t hardcode the DSN in your code.');
        }

        ///////////////////////////////////////////////////////////////
        // extensions configurations
        ///////////////////////////////////////////////////////////////

        // Apache
        $suggestion = $this->checkPresence('Extensions\\Extapache');
        if ($suggestion == 'On') {
            $this->array['Apache'] = array();
            $this->array['Apache'] = array();
            $this->array['Apache'][]  = array('name' => 'child_terminate',
                                              'suggested' => 'true', 
                                              'documentation' => 'Specify whether PHP scripts may request child process termination on end of request.');

            $this->array['Apache'][]  = array('name' => 'Extra configurations',
                                              'suggested' => '&nbsp;',
                                              'documentation' => '<a href="http://php.net/manual/en/apache.configuration.php">Apache runtime configuration</a>');
        }
        
        // Curl
        $suggestion = $this->checkPresence('Extensions\\Extcurl');
        if ($suggestion == 'On') {
            $this->array['Curl'] = array();
            $this->array['Curl'][]  = array('name' => 'Extra configurations',
                                            'suggested' => '&nbsp;',
                                            'documentation' => '<a href="http://php.net/manual/en/curl.configuration.php">Curl runtime configuration</a>');
        } 

        // File Upload
        $suggestion = $this->checkPresence('Structures\\FileUploadUsage');
        $this->array['File upload'] = array();
        if ($suggestion == 'On') {
            $this->array['File upload'][] = array('name' => 'upload_max_filesize',
                                                  'documentation' => 'This is the maximum uploaded size. It is recommended to keep this value as low as possible.',
                                                  'suggested' => '2M');

            $this->array['File upload'][] = array('name' => 'max_file_uploads',
                                                  'documentation' => 'This is the maximum number of uploaded files in a single request. Each file will be .',
                                                  'suggested' => '1');
        } else {
            $this->array['File upload'][] = array('name' => 'file_uploads',
                                                 'documentation' => 'Since the application doesn\'t handle uploaded files, it is recommended to disable this option, saving memory, and disabling features that may be a security vulnerability later.',
                                                 'suggested' => $suggestion = $this->checkPresence('Structures\\FileUploadUsage'));
        }

        // Intl
        $suggestion = $this->checkPresence('Extensions\\Extintl');
        if ($suggestion == 'On') {
            $this->array['ext/Intl'] = array();
            $this->array['ext/Intl'][] = array('name' => 'intl.default_locale',
                                               'suggested' => '<Your ICU Locale>',
                                               'documentation' => 'The locale that will be used in intl functions when none is specified (either by omitting the corresponding argument or by passing NULL). These are ICU locales, not system locales. ');

            $this->array['ext/Intl'][] = array('name' => 'intl.error_level',
                                               'suggested' => 'E_WARNING',
                                               'documentation' => 'The level of the error messages generated when an error occurs in ICU functions. This is a PHP error level, such as E_WARNING. It can be set to 0 in order to inhibit the messages. This does not affect the return values indicating error or the values returned by intl_get_error_code() or by the class specific methods for retrieving error codes and messages. Choosing E_ERROR will terminate the script whenever an error condition is found on intl classes.');

            $this->array['ext/Intl'][] = array('name' => 'intl.use_exceptions',
                                               'suggested' => 'false',
                                               'documentation' => 'If set to true, an exception will be raised whenever an error occurs in an intl function. The exception will be of type IntlException. This is possibly in addition to the error message generated due to intl.error_level.');

            $this->array['ext/Intl'][]  = array('name' => 'Extra configurations',
                                                'suggested' => '&nbsp;',
                                                'documentation' => '<a href="http://php.net/manual/en/intl.configuration.php">Intl runtime configuration</a>');
        } 

        // Mongo
        $suggestion = $this->checkPresence('Extensions\\Extmongo');
        if ($suggestion == 'On') {
            $this->array['Mongo'] = array();
            $this->array['Mongo'][]  = array('name' => 'mongo.default_host',
                                             'suggested' => 'localhost',
                                             'documentation' => 'The default Mongo host to connect to.');
            $this->array['Mongo'][]  = array('name' => 'mongo.default_port',
                                             'suggested' => '27017',
                                             'documentation' => 'The default Mongo port to connect to.');
            $this->array['Mongo'][]  = array('name' => 'mongo.native_long',
                                             'suggested' => '1',
                                             'documentation' => 'Mongo handles integers as 64bits on plat-forms that actually handles them. If not, it will be handled as 32 bits.');
            $this->array['Mongo'][]  = array('name' => 'mongo.long_as_object',
                                             'suggested' => '1',
                                             'documentation' => 'Return a BSON_LONG as an instance of MongoInt64 (instead of a primitive type).');
            $this->array['Mongo'][]  = array('name' => 'mongo.utf8',
                                             'suggested' => '1',
                                             'documentation' => 'Ensure that Mongo handles UTF-8 correctly. ');
            $this->array['Mongo'][]  = array('name' => 'Extra configurations',
                                             'suggested' => '&nbsp;',
                                             'documentation' => '<a href="http://php.net/manual/en/mongo.configuration.php">Mongo runtime configuration</a>');
        }

        // Opcache
        $suggestion = $this->checkPresence('Extensions\\Extopcache');
        if ($suggestion == 'On') {
            $this->array['Opcache'] = array();
            $this->array['Opcache'][]  = array('name' => 'opcache.enable',
                                               'suggested' => 'On',
                                               'documentation' => 'By putting aliases of URI in the php.ini, you won\'t hardcode the DSN in your code.');

            $this->array['Opcache'][]  = array('name' => 'opcache.memory_consumption',
                                               'suggested' => '128',
                                               'documentation' => 'This directive set the amount of opcode cache. The more the better, as long as it doesn\'t swap.');

            $this->array['Opcache'][]  = array('name' => 'opcache.memory_consumption',
                                               'suggested' => '4000',
                                               'documentation' => 'The maximum number of files OPcache will cache. Estimate 32kb a file.');
            $this->array['Opcache'][]  = array('name' => 'Extra configurations',
                                               'suggested' => '&nbsp;',
                                               'documentation' => '<a href="http://php.net/manual/en/opcache.configuration.php">Opcache runtime configuration</a>');
        }

        // Wincache
        $suggestion = $this->checkPresence('Extensions\\Extwincache');
        if ($suggestion == 'On') {
            $this->array['Wincache'] = array();
            $this->array['Wincache'][]  = array('name' => 'wincache.ocenabled',
                                                'suggested' => 'true', 
                                                'documentation' => 'Enables or disables the wincache opcode cache functionality.');

            $this->array['Wincache'][]  = array('name' => 'wincache.ocachesize',
                                                'suggested' => '255', 
                                                'documentation' => 'Defines the maximum memory size (in megabytes) that is allocated for the opcode cache. Max value is 255 (Mb).');

            $this->array['Wincache'][]  = array('name' => 'wincache.ttlmax',
                                                'suggested' => '1200', 
                                                'documentation' => 'Defines the maximum time to live (in seconds) for a cached entry without being used. Setting it to 0 will disable the cache scavenger, so the cached entries will never be removed from the cache during the lifetime of the IIS worker process.');
            $this->array['Wincache'][]  = array('name' => 'Extra configurations',
                                                'suggested' => '&nbsp;',
                                                'documentation' => '<a href="http://php.net/manual/en/wincache.configuration.php">Wincache runtime configuration</a>');
        }
        
        // Xcache
        $suggestion = $this->checkPresence('Extensions\\Extxcache');
        if ($suggestion == 'On') {
            $this->array['Xcache'] = array();
            $this->array['Xcache'][]  = array('name' => 'xcache.cacher',
                                              'suggested' => 'true', 
                                              'documentation' => 'Enable or disable opcode cacher. Not available if xcache.size is 0.');

            $this->array['Xcache'][]  = array('name' => 'xcache.size',
                                              'suggested' => '1024K', 
                                              'documentation' => 'Total amount of memory used for opcode (*.php) caching. If set to 0 - opcode caching is disabled. K M G modifiers can be used, i.e. 1G 512M 1024K');

            $this->array['Xcache'][]  = array('name' => 'xcache.admin.enable_auth',
                                              'suggested' => 'on', 
                                              'documentation' => 'Disable XCache builtin http authentication if you plan on handling authentication yourself. Be aware that any vhost users can set up admin page, if builtin http auth is disabled, they can access the page with out any authentication. So it is suggested that you disable mod_auth for XCache admin pages instead of disabling XCache builtin auth.');

            $this->array['Xcache'][]  = array('name' => 'xcache.admin.user',
                                              'suggested' => '1024K', 
                                              'documentation' => 'Authentification name.');

            $this->array['Xcache'][]  = array('name' => 'xcache.admin.pass',
                                              'suggested' => '<md5(your_password)>', 
                                              'documentation' => 'Should be md5($your_password), or empty to disable administration.');

            $this->array['Xcache'][]  = array('name' => 'xcache.optimizer',
                                              'suggested' => 'true', 
                                              'documentation' => 'Enable xcache optimizer.');

            $this->array['Xcache'][]  = array('name' => 'xcache.coverager',
                                              'suggested' => 'false', 
                                              'documentation' => 'Enable xcache scavenger.');

            $this->array['Xcache'][]  = array('name' => 'Extra configurations',
                                               'suggested' => '&nbsp;',
                                               'documentation' => '<a href="http://php.net/manual/en/xcache.configuration.php">Xcache runtime configuration</a>');
        }

    }

    private function checkPresence($analyzer) {
        $vertices = $this->query("g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('\\', '\\\\', $analyzer)."']].out.any()");
        return $vertices[0][0] === false ? 'Off' : 'On';
    }
}

?>
