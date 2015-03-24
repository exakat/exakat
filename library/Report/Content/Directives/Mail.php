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

class Mail extends Directives {
    function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'Mail';
        
        if ($this->checkPresence('Extensions\\Extmail')) {
            $this->directives[] = array('name' => 'SMTP',
                                        'suggested' => 'localhost',
                                        'documentation' => 'Used under Windows only: the host that will be sending the mail.');

            $this->directives[] = array('name' => 'smtp_port',
                                        'suggested' => '25',
                                        'documentation' => 'Used under Windows only: the port on the host that will be sending the mail.');

            $this->directives[] = array('name' => 'sendmail_path',
                                        'suggested' => '/usr/sbin/sendmail -t -i',
                                        'documentation' => 'The location of the mail sending program (here, smtp, but it may also be qmail or else). Configure will try to locate smtp, but if it fails, you may set this directive correctly.');

            $this->directives[] = array('name' => 'sendmail_from',
                                        'suggested' => 'your-email@your-domain.com',
                                        'documentation' => 'Indicates an origin for any mail being send with PHP. This should be set, so as to avoid being mistaken as spam, and provide an with which communicate in case of any problem.');

            $this->directives[] = array('name' => 'mail.log',
                                        'suggested' => '/var/log/phpmail.log',
                                        'documentation' => 'Keep log of mails being send with PHP. Be careful if the data being send are sensitive (destination is noted). ');

            $this->directives[] = array('name' => 'mail.add_x_header',
                                        'suggested' => 'your-domain.com',
                                        'documentation' => 'Adds a special X-PHP-Originating-Script headers, providing more information on the origin of the mail, but which will be mostly hidden from the final reader (unless it checks the mail headers).');

            $this->directives[] = $this->extraConfiguration($this->name, 'mail');
        }
    }
}

?>