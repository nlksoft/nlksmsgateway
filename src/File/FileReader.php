<?php
/**
 * Created by PhpStorm.
 * User: Abdulkadir kar
 * Date: 31.01.2018
 * Time: 11:45
 */

namespace Nlksoft\SmsGateway\File;


interface FileReader {
    public function read( $path );
}