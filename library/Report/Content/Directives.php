<?php

namespace Report\Content;

class Directives extends \Report\Content {
    protected $name = 'Directives';
    protected $array = array();
    
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

        // PDO
        $this->array['PDO'] = array();
        $this->array['PDO'][]  = array('name' => 'pdo.dns.*',
                                       'suggested' => 'sqlite:/opt/databases/mydb.sq3', // found value in the code
                                       'documentation' => 'By putting aliases of URI in the php.ini, you won\'t hardcode the DSN in your code.');

        // File Upload
        $this->array['File upload'] = array();
        $file_uploads = array('name' => 'file_uploads',
                              'documentation' => 'Since the application doesn\'t handle uploaded files, it is recommended to disable this option, saving memory, and disabling features that may be a security vulnerability later.',
                              'suggested' => $suggestion = $this->checkPresence('Structures\\FileUploadUsage'));
        $this->array['File upload'][] = $file_uploads;
        
        if ($suggestion == 'On') {
            $upload_max_filesize = array('name' => 'upload_max_filesize',
                                         'documentation' => 'This is the maximum uploaded size. It is recommended to keep this value as low as possible.',
                                         'suggested' => '2M');
            $this->array['File upload'][] = $upload_max_filesize;

            $upload_max_file = array('name' => 'max_file_uploads',
                                     'documentation' => 'This is the maximum number of uploaded files in a single request. Each file will be .',
                                     'suggested' => '1');
            $this->array['File upload'][] = $upload_max_file;
        }


    }
    
    private function checkPresence($analyzer) {
        $vertices = $this->query("g.idx('analyzers')[['analyzer':'Analyzer\\\\".str_replace('\\', '\\\\', $analyzer)."']].out.any()");
        return $vertices[0][0] == false ? 'Off' : 'On';

    }
}

?>
