<?php

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
        $this->array['Filter'] = array();
        if ($suggestion == 'On') {
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
        $suggestion = $this->checkPresence('Extensions\\Extxcache');
        $this->array['Apache'] = array();
        if ($suggestion == 'On') {
            $this->array['Apache'] = array();
            $this->array['Apache'][]  = array('name' => 'child_terminate',
                                              'suggested' => 'true', 
                                              'documentation' => 'Specify whether PHP scripts may request child process termination on end of request.');
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
        }
        
        // File Upload
        $suggestion = $this->checkPresence('Structures\\FileUploadUsage');
        $this->array['File upload'] = array();
        if ($suggestion == 'On') {
            $upload_max_filesize = array('name' => 'upload_max_filesize',
                                         'documentation' => 'This is the maximum uploaded size. It is recommended to keep this value as low as possible.',
                                         'suggested' => '2M');
            $this->array['File upload'][] = $upload_max_filesize;

            $upload_max_file = array('name' => 'max_file_uploads',
                                     'documentation' => 'This is the maximum number of uploaded files in a single request. Each file will be .',
                                     'suggested' => '1');
            $this->array['File upload'][] = $upload_max_file;
        } else {
            $file_uploads = array('name' => 'file_uploads',
                                  'documentation' => 'Since the application doesn\'t handle uploaded files, it is recommended to disable this option, saving memory, and disabling features that may be a security vulnerability later.',
                                  'suggested' => $suggestion = $this->checkPresence('Structures\\FileUploadUsage'));
            $this->array['File upload'][] = $file_uploads;
        }

        // Intl
        $suggestion = $this->checkPresence('Extensions\\Extintl');
        $this->array['ext/Intl'] = array();
        if ($suggestion == 'On') {
            $directive = array('name' => 'intl.default_locale',
                               'suggested' => '<Your ICU Locale>',
                               'documentation' => 'The locale that will be used in intl functions when none is specified (either by omitting the corresponding argument or by passing NULL). These are ICU locales, not system locales. ');
            $this->array['ext/Intl'][] = $directive;

            $directive = array('name' => 'intl.error_level',
                               'suggested' => 'E_WARNING',
                               'documentation' => 'The level of the error messages generated when an error occurs in ICU functions. This is a PHP error level, such as E_WARNING. It can be set to 0 in order to inhibit the messages. This does not affect the return values indicating error or the values returned by intl_get_error_code() or by the class specific methods for retrieving error codes and messages. Choosing E_ERROR will terminate the script whenever an error condition is found on intl classes.');
            $this->array['ext/Intl'][] = $directive;

            $directive = array('name' => 'intl.use_exceptions',
                               'suggested' => 'false',
                               'documentation' => 'If set to true, an exception will be raised whenever an error occurs in an intl function. The exception will be of type IntlException. This is possibly in addition to the error message generated due to intl.error_level.');
            $this->array['ext/Intl'][] = $directive;
        } 

        // Xcache
        $suggestion = $this->checkPresence('Extensions\\Extxcache');
        $this->array['Xcache'] = array();
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
        }
        
        // Wincache
        $suggestion = $this->checkPresence('Extensions\\Extwincache');
        $this->array['Wincache'] = array();
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
        }

    }

    private function checkPresence($analyzer) {
        $vertices = $this->query("g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('\\', '\\\\', $analyzer)."']].out.any()");
        return $vertices[0][0] === false ? 'Off' : 'On';
    }
}

?>
