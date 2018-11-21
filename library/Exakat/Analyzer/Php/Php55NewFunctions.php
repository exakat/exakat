<?php
/*
 * Copyright 2012-2018 Damien Seguy – Exakat SAS <contact(at)exakat.io>
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

use Exakat\Analyzer\Analyzer;
use Exakat\Analyzer\Common\FunctionDefinition;

class Php55NewFunctions extends FunctionDefinition {
    protected $phpVersion = '5.5-';
    
    public function analyze() {
        $this->functions = array(
'array_column',
'boolval',
'cli_get_process_title',
'cli_set_process_title',
'curl_escape',
'curl_file_create',
'curl_multi_setopt',
'curl_multi_strerror',
'curl_pause',
'curl_reset',
'curl_share_close',
'curl_share_init',
'curl_share_setopt',
'curl_strerror',
'curl_unescape',
'datefmt_format_object',
'datefmt_get_calendar_object',
'datefmt_get_calendar_object',
'datefmt_get_timezone',
'datefmt_set_timezone',
'hash_pbkdf2',
'imageaffinematrixconcat',
'imageaffinematrixget',
'imagecrop',
'imagecropauto',
'imageflip',
'imagepalettetotruecolor',
'imagescale',
'intlcal_add',
'intlcal_after',
'intlcal_before',
'intlcal_clear',
'intlcal_create_instance',
'intlcal_equals',
'intlcal_field_difference',
'intlcal_from_date_time',
'intlcal_get_actual_maximum',
'intlcal_get_actual_minimum',
'intlcal_get_available_locales',
'intlcal_get_day_of_week_type',
'intlcal_get_error_code',
'intlcal_get_error_message',
'intlcal_get_first_day_of_week',
'intlcal_get_greatest_minimum',
'intlcal_get_keyword_values_for_locale',
'intlcal_get_least_maximum',
'intlcal_get_locale',
'intlcal_get_maximum',
'intlcal_get_minimal_days_in_first_week',
'intlcal_get_minimum',
'intlcal_get_now',
'intlcal_get_repeated_wall_time_option',
'intlcal_get_skipped_wall_time_option',
'intlcal_get_time_zone',
'intlcal_get_time',
'intlcal_get_type',
'intlcal_get_weekend_transition',
'intlcal_get',
'intlcal_in_daylight_time',
'intlcal_is_equivalent_to',
'intlcal_is_lenient',
'intlcal_is_set',
'intlcal_is_weekend',
'intlcal_roll',
'intlcal_set_first_day_of_week',
'intlcal_set_lenient',
'intlcal_set_repeated_wall_time_option',
'intlcal_set_skipped_wall_time_option',
'intlcal_set_time_zone',
'intlcal_set_time',
'intlcal_set',
'intlcal_to_date_time',
'intlgregcal_create_instance',
'intlgregcal_get_gregorian_change',
'intlgregcal_is_leap_year',
'intlgregcal_set_gregorian_change',
'intltz_count_equivalent_ids',
'intltz_create_default',
'intltz_create_enumeration',
'intltz_create_time_zone_id_enumeration',
'intltz_create_time_zone',
'intltz_from_date_time_zone',
'intltz_get_canonical_id',
'intltz_get_display_name',
'intltz_get_dst_savings',
'intltz_get_equivalent_id',
'intltz_get_error_code',
'intltz_get_error_message',
'intltz_get_gmt',
'intltz_get_id',
'intltz_get_offset',
'intltz_get_raw_offset',
'intltz_get_region',
'intltz_get_tz_data_version',
'intltz_get_unknown',
'intltz_has_same_rules',
'intltz_to_date_time_zone',
'intltz_use_daylight_time',
'json_last_error_msg',
'mysqli_begin_transaction',
'mysqli_release_savepoint',
'mysqli_savepoint',
'openssl_pbkdf2',
'password_get_info',
'password_hash',
'password_needs_rehash',
'password_verify',
'pg_escape_identifier',
'pg_escape_literal',
'socket_cmsg_space',
'socket_recvmsg',
'socket_sendmsg',
);
        parent::analyze();
    }
}

?>
