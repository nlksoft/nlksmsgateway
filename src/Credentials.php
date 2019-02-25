<?php
/**
 * Created by PhpStorm.
 * User: Abdulkadir kar
 * Date: 31.01.2018
 * Time: 11:48
 */

namespace Nlksoft\SmsGateway;

use Nlksoft\SmsGateway\Exception\ClientException;
use Nlksoft\SmsGateway\Exception\SmsException;
use Nlksoft\SmsGateway\Exception\AuthenticationException;


class Credentials {
    private $username = '';
    private $password = '';
    private $endpoint = "api.mesajpaneli.com/json_api";

    public function __construct( $data ) {


          $this->username = $data['user']['name'];
          $this->password = $data['user']['pass'];



        $this->validate();
    }

    private function validate() {
        if ( ! $this->username || ! $this->password ) {
            throw new AuthenticationException( "Kullanıcı adı ve şifrenizi kontrol ediniz." );
        }

         $this->endpoint = ( strpos( $this->endpoint, 'http://' ) === 0 ) ? 'http://' . $this->endpoint : $this->endpoint;


    }

    public function getAsArray() {
        $this->validate();

        return [
            'user' => [
                'name' => $this->username,
                'pass' => $this->password
            ]
        ];
    }
}
