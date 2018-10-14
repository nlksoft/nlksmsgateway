<?php
/**
 * Created by PhpStorm.
 * User: Abdulkadir kar
 * Date: 31.01.2018
 * Time: 11:50
 */

namespace Nlksoft\SmsGateway;


class  Curl {

    static $handle; // Handle
    static $body = ''; // Response body
    static $head = ''; // Response head
    static $info = [];

    static function head( $ch, $data ) {
        self::$head = $data;
        return strlen( $data );
    }

    static function body( $ch, $data ) {
        self::$body .= $data;
        return strlen( $data );
    }

    static function fetch( $url, $opts = [] ) {
        self::$head = self::$body = '';

        self::$info = [];
        self::$handle = curl_init( $url );
        curl_setopt_array( self::$handle, $opts );
        curl_exec( self::$handle );
        self::$info = curl_getinfo( self::$handle );
        curl_close( self::$handle );
    }
}