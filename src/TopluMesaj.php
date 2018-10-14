<?php
/**
 * Created by PhpStorm.
 * User: Abdulkadir kar
 * Date: 31.01.2018
 * Time: 11:51
 */

namespace Nlksoft;


Class TopluMesaj {
    private $tel = [];
    private $msg;

    /**
     * @param $mesajMetni
     * @param array|string $numaralar
     */
    public function __construct( $mesajMetni, $numaralar = '' ) {
        $this->msg = $mesajMetni;
        if ( is_array( $numaralar ) )
            $this->tel = $numaralar;
        else
            $this->tel = explode( ',', $numaralar );
    }

    public function numaraEkle( $gsm ) {
        $this->tel[] = $gsm;
    }

    public function getAsArray() {
        return [
            'tel' => $this->tel,
            'msg' => $this->msg
        ];
    }
}