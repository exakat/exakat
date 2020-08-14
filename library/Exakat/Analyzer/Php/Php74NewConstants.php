<?php declare(strict_types = 1);
/*
 * Copyright 2012-2019 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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


namespace Exakat\Analyzer\Php;

use Exakat\Analyzer\Common\ConstantDefinition;

class Php74NewConstants extends ConstantDefinition {
    public function analyze(): void {
        $this->constants = array('MB_ONIGURUMA_VERSION',
                                 'SO_LABEL',
                                 'SO_PEERLABEL',
                                 'SO_LISTENQLIMIT',
                                 'SO_LISTENQLEN',
                                 'SO_USER_COOKIE',
                                 'PHP_WINDOWS_EVENT_CTRL_C',
                                 'PHP_WINDOWS_EVENT_CTRL_BREAK',
                                 'TIDY_TAG_ARTICLE',
                                 'TIDY_TAG_ASIDE',
                                 'TIDY_TAG_AUDIO',
                                 'TIDY_TAG_BDI',
                                 'TIDY_TAG_CANVAS',
                                 'TIDY_TAG_COMMAND',
                                 'TIDY_TAG_DATALIST',
                                 'TIDY_TAG_DETAILS',
                                 'TIDY_TAG_DIALOG',
                                 'TIDY_TAG_FIGCAPTION',
                                 'TIDY_TAG_FIGURE',
                                 'TIDY_TAG_FOOTER',
                                 'TIDY_TAG_HEADER',
                                 'TIDY_TAG_HGROUP',
                                 'TIDY_TAG_MAIN',
                                 'TIDY_TAG_MARK',
                                 'TIDY_TAG_MENUITEM',
                                 'TIDY_TAG_METER',
                                 'TIDY_TAG_NAV',
                                 'TIDY_TAG_OUTPUT',
                                 'TIDY_TAG_PROGRESS',
                                 'TIDY_TAG_SECTION',
                                 'TIDY_TAG_SOURCE',
                                 'TIDY_TAG_SUMMARY',
                                 'TIDY_TAG_TEMPLATE',
                                 'TIDY_TAG_TIME',
                                 'TIDY_TAG_TRACK',
                                 'TIDY_TAG_VIDEO',
                                 'STREAM_CRYPTO_METHOD_TLSv1_3_CLIENT',
                                 'STREAM_CRYPTO_METHOD_TLSv1_3_SERVER',
                                 'STREAM_CRYPTO_PROTO_TLSv1_3',
                                 'T_COALESCE_EQUAL',
                                 'T_FN',
        );
        parent::analyze();
    }
}

?>
