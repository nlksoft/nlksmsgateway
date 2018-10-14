<?php
/**
 * Created by PhpStorm.
 * User: Abdulkadir kar
 * Date: 31.01.2018
 * Time: 11:48
 */

namespace Nlksoft\SmsGateway;

use Nlksoft\File\FileReader;

class ConfigurationReader implements FileReader {

    public function read( $path ) {
        if ( is_readable( $path ) ) {
            # Remember that file_get_contents() will not work if your server has *allow_url_fopen* turned off.
            return file_get_contents( $path );
        }

        return false;
    }
}
