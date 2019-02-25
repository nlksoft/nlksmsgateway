<?php
/**
 * Created by PhpStorm.
 * User: Abdulkadir kar
 * Date: 31.01.2018
 * Time: 11:47
 */

namespace Nlksoft\SmsGateway;


use Nlksoft\SmsGateway\Exception\ClientException;
use Nlksoft\SmsGateway\Exception\SmsException;
use Nlksoft\SmsGateway\Exception\AuthenticationException;



class User {
    private $userInfo = [];

    public function __construct( $userInfo ) {
        if ( ! is_array( $userInfo ) || ! isset( $userInfo['userData'] ) ) {
            throw new AuthenticationException( "UserInfo array olmalıdır." );
        }

        $this->userInfo = $userInfo['userData'];
    }

    public function getMid() {
        return $this->userInfo['musteriid'];
    }

    public function getBid() {
        return $this->userInfo['bayiiid'];
    }

    public function getMik() {
        return $this->userInfo['musterikodu'];
    }

    public function getName() {
        return $this->userInfo['yetkiliadsoyad'];
    }

    public function getCompany() {
        return $this->userInfo['firma'];
    }

    public function getNumericBalance() {
        return $this->userInfo['sistem_kredi'];
    }

    public function getSenders() {
        return $this->userInfo['basliklar']; # array
    }

    public function getOriginatedBalance() {
        return $this->userInfo['orjinli'];
    }
}
