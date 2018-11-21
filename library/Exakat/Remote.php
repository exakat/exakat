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


namespace Exakat;

class Remote {
    private $bits = array();
    private $key  = '';

    public function __construct($url = '/tmp/exakat.queue', $key = '') {
        $bits = parse_url($url);
        $this->bits = array( 'scheme' => $bits['scheme'] ?? 'http',
                             'host'   => $bits['host']   ?? 'localhost',
                             'port'   => $bits['port']   ?? '8447',
                             'path'   => $bits['path']   ?? '/tmp/exakat.queue',
                           );
        $this->key = $key;
    }

    public function send($json) {
        if ($this->bits['scheme'] === 'file') {
            return $this->sendWithPipe($json);
        } elseif ($this->bits['scheme'] === 'http') {
            return $this->sendWithHTTP($json);
        } else {
            // Throw error
        }
    }
    
    private function sendWithPipe($json) {
        $queuePipe = fopen($this->bits['path'], 'w');
        fwrite($queuePipe, $json.PHP_EOL);
        fclose($queuePipe);
    }

    private function sendWithHTTP($json) {
        if (!empty($this->key)) {
            $json = $this->safeEncrypt($json, $this->key);
        }
        $json = urlencode($json);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => "json=$json",
                           ),
        );

        $context  = stream_context_create($options);
        $html = file_get_contents($this->bits['scheme'].'://'.$this->bits['host'].':'.$this->bits['port'], false, $context);
        
        return $html;
    }

    /**
    * Encrypt a message
    *
    * @param string $message - message to encrypt
    * @param string $key - encryption key
    * @return string
    */
    private function safeEncrypt($message, $key)
    {
        $nonce = random_bytes(
            SODIUM_CRYPTO_SECRETBOX_NONCEBYTES
        );
    
        $cipher = base64_encode(
            $nonce.
            sodium_crypto_secretbox(
                $message,
                $nonce,
                $key
            )
        );
        sodium_memzero($message);
        sodium_memzero($key);
        return $cipher;
    }
}

?>
