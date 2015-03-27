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

class Session extends Directives {
    public function __construct(Client $neo4j) {
        parent::__construct($neo4j);
        $this->name         = 'Session';
        
        if ($this->checkPresence('Extensions\\Extsession')) {
            $this->directives[] = array('name'          => 'session.name',
                                        'suggested'     => '<Some name linked to your application>',
                                        'documentation' => 'This directive sets the name of the session, which is also used as cookie. It is recommended to give an explicit name to this, and avoid the default value of PHPSESSID.');

            $this->directives[] = array('name'          => 'session.path',
                                        'suggested'     => 'Avoid /tmp',
                                        'documentation' => 'This directive sets the path where the session files will be store (if using a file storage). It is recommended to avoid using /tmp, as this folder is accessible to everyone who has access to the machine. Set it to some path that is dedicated to the webserver.');

            $this->directives[] = array('name'          => 'session.auto_start',
                                        'suggested'     => '1',
                                        'documentation' => 'This directive allows the session to be started at request time. This is the default behavior for most web sites. ');

            $this->directives[] = array('name'          => 'session.cookie_httponly',
                                        'suggested'     => '1',
                                        'documentation' => 'Mark the session cookie as reserved for HTTP communication. This will prevent the cookie to be available for Javascript, and help prevent XSS (although, not all browsers support it).');

            $this->directives[] = array('name'          => 'session.use_only_cookies',
                                        'suggested'     => '1',
                                        'documentation' => 'Limit the transmission of the session id to cookies.');

            $this->directives[] = array('name'          => 'session.use_trans_sid',
                                        'suggested'     => '0',
                                        'documentation' => 'This will make PHP put the session token in the URL, instead of cookies. This is a security risk, as the token may be easily accessed and shared. It is recommended to avoid this.');

            $this->directives[] = array('name'          => 'session.cookie_domain',
                                        'suggested'     => '<yourdomain.net>',
                                        'documentation' => 'This directive will limit the diffusion of the session cookie to the specified domain name. The more restrictive the better. Aka, session.cookie_domain=".net" will restrict the cookie to every ".net" domains, and not every domain. session.cookie_domain="www.yourdomain.net" will restrict it to the eponymous domain, and won\'t share the cookie with "images.yourdomain.net", which may be too restrictive.');

            $this->directives[] = $this->extraConfiguration($this->name, 'session');
        }
    }
}

?>