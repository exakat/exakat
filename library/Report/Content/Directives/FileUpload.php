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


namespace Report\Content\Directives;

use Everyman\Neo4j\Client;

class FileUpload extends Directives {
    public function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'File Upload';

        if ($this->checkPresence('Structures\\FileUploadUsage')) {
            $this->directives[] = array('name' => 'upload_max_filesize',
                                        'documentation' => 'This is the maximum uploaded size. It is recommended to keep this value as low as possible.',
                                        'suggested' => '2M');

            $this->directives[] = array('name' => 'max_file_uploads',
                                        'documentation' => 'This is the maximum number of uploaded files in a single request. Each file will be .',
                                        'suggested' => '1');
        } else {
            $this->array['File upload'][] = array('name' => 'file_uploads',
                                                 'documentation' => 'Since the application doesn\'t handle uploaded files, it is recommended to disable this option, saving memory, and disabling features that may be a security vulnerability later.',
                                                 'suggested' => 0);
        }
    }
}

?>