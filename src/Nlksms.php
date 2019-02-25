<?php

namespace Nlksoft\SmsGateway;

use Nlksoft\SmsGateway\Exception\ClientException;
use Nlksoft\SmsGateway\Exception\SmsException;
use Nlksoft\SmsGateway\Exception\AuthenticationException;


class Nlksms {
    private $actions;
    private $parametricMessages = [];

    /**
     * MesajPaneli Client constructor.
     *
     * @param string $configFileName
     *


     * @throws ClientException
     */
    public function __construct( $config = array(
        'user' => array('name'=>'','pass'=>'')
    ) ) {
        $this->actions = new UserActions( new Credentials( $config ) );

        if ( ! $this->actions ) {
            throw new ClientException( "Müşteri bilgileri doğrulanamadı. Bilgilerinizi kontrol edin." );
        }
    }

    ##### Kullanıcı Bilgileri Fonksiyonları #####

    /**
     * User objesini döndürür.
     *
     * Beklenen array:
     * $this->credentialsArray = [ 'name' => 'kullaniciAdi', 'pass' => 'sifre' ];
     *
     * Bilgiler doğru girildiğinde:
     * {"userData":{"musteriid":"12345678","bayiid":"2415","musterikodu":"Demo","yetkiliadsoyad":"Demo","firma":"Demo","orjinli":"0","sistem_kredi":"0","basliklar":["850"]},"status":true}
     *
     * Bilgiler yanlış girildiğinde:
     * {"status":false,"error":"Hatali kullanici adi, sifre girdiniz. Lutfen tekrar deneyiniz."}
     *
     * @return User
     * @throws AuthenticationException
     */
    public function getUser() {
        return $this->actions->getUser();
    }

    public function baslikliKrediSorgula() {
        return $this->actions->getUser()->getOriginatedBalance();
    }

    public function numerikKrediSorgula() {
        return $this->actions->getUser()->getNumericBalance();
    }

    public function kayitliBasliklar() {
        return $this->actions->getUser()->getSenders();
    }

    public function musteriID() {
        return $this->actions->getUser()->getMid();
    }


    /**
     * Kullanıcı şifresi değiştirme metodu
     *
     * @param string $yeniSifre
     *
     * @return string
     * @throws AuthenticationException
     *
     * Beklenen array:
     * $this->credentialsArray = [ 'name' => 'kullaniciAdi', 'pass' => 'eskiSifre', 'newpass' => 'yeniSifre' ];
     *
     * */
    public function sifreDegistir( $yeniSifre ) {
        return $this->actions->resetPassword( $yeniSifre );
    }

    /**
     * Hatalı kredi iade yapma metodu
     *
     * @param int $ref
     *
     * @return string
     * @throws AuthenticationException
     */
    public function hataliKrediIade( $ref ) {
        return $this->actions->refund( $ref );
    }

    ##### Mesaj Gönderim Fonksiyonları #####

    /**
     * Toplu mesaj gönderimi
     *
     * @param string $baslik
     * @param TopluMesaj|array $data
     * @param bool $tr
     * @param null|int $gonderimZamani
     *
     * @return string
     * @throws SmsException
     */
    public function topluMesajGonder( $baslik, $data, $tr = false, $gonderimZamani = null ) {
        return $this->actions->bulkSMS( $baslik, $data, $tr, $gonderimZamani );
    }

    /**
     * Parametrik mesaj gönderimi için gsm ve mesaj ekleme metodu.
     * Bu fonksiyon ile gsm ve mesajları tek tek ekliyorsanız,
     * parametrikMesajGonder fonksiyonunda $data arrayini null giriniz.
     *
     * @param string $gsm
     * @param string $mesaj
     *
     * @return void
     */
    public function parametrikMesajEkle( $gsm, $mesaj ) {
        $this->parametricMessages[] = [ 'tel' => $gsm, 'msg' => $mesaj ];
    }

    /**
     * Parametrik mesaj gönderimi
     *
     * @param $baslik
     * @param null|array $data
     * @param bool $tr
     * @param null|int $gonderimZamani
     * @param bool $unique
     *
     * @return string
     * @throws SmsException
     */
    public function parametrikMesajGonder( $baslik, $data = null, $tr = false, $gonderimZamani = null, $unique = true ) {
        if ( is_null( $data ) ) {
            $data = $this->parametricMessages;
        }

        $response = $this->actions->parametricSMS( $baslik, $data, $tr, $gonderimZamani, $unique );

        $this->parametricMessages = [];

        return $response;
    }

    ##### Rapor Alma Fonksiyonları #####

    /**
     * Referans No ile rapor detayları
     *
     * @param $ref
     * @param null|bool $tarihler
     * @param null|bool $operatorler
     *
     * @return string
     * @throws SmsException
     */
    public function raporDetay( $ref, $tarihler = null, $operatorler = null ) {
        return $this->actions->reportDetails( $ref, $tarihler, $operatorler );
    }

    /**
     * Tüm raporlar
     *
     * @param null|array $tarihler
     * @param null|int $limit
     *
     * @return string
     */
    public function raporListele( $tarihler = null, $limit = null ) {
        return $this->actions->listReports( $tarihler, $limit );
    }

    ##### Telefon Defteri Fonksiyonları #####

    /**
     * Tüm telefon defteri gruplarını getir
     *
     * @return string
     * @throws AuthenticationException
     */
    public function telefonDefteriGruplar() {
        return $this->actions->getAddressBooks();
    }

    /**
     * Telefon defterine yeni grup ekle
     *
     * @param string $title
     *
     * @return string
     * @throws AuthenticationException
     */
    public function yeniGrup( $title ) {
        return $this->actions->createAddressBook( $title );
    }

    /**
     * Telefon defterinden grup sil
     *
     * @param int $id
     *
     * @return string
     * @throws AuthenticationException
     */
    public function grubuSil( $id ) {
        return $this->actions->deleteAddressBook( $id );
    }

    /**
     * Gruba kişi/numara ekle
     *
     * @param int $grupID
     * @param array $numaralar
     *
     * @return string
     * @throws AuthenticationException
     */
    public function numaraEkle( $grupID, $numaralar ) {
        return $this->actions->addContact( $grupID, $numaralar );
    }

    /**
     * Gruptan kişi/numara çıkar
     *
     * @param int $grupID
     * @param array $numaralar
     *
     * @return string
     * @throws AuthenticationException
     */
    public function numaraCikar( $grupID, $numaralar ) {
        return $this->actions->removeContact( $grupID, $numaralar );
    }

    /**
     * Gruba kayıtlı tüm kişiler
     *
     * @param int $grupID
     *
     * @return string
     * @throws AuthenticationException
     */
    public function gruptakiKisiler( $grupID ) {
        return $this->actions->getContactsByGroupID( $grupID );
    }

    /**
     * Bir numarayı tüm gruplarda ara
     *
     * @param string $numara
     *
     * @return string
     * @throws AuthenticationException
     */
    public function tumGruplardaAra( $numara ) {
        return $this->actions->searchNumberInGroups( $numara );
    }

    /**
     * Grupta bir numara ara
     *
     * @param string $numara
     * @param int $grupID
     *
     * @return string
     * @throws AuthenticationException
     */
    public function gruptaAra( $numara, $grupID ) {
        return $this->actions->searchNumberInGroup( $numara, $grupID );
    }

    /**
     * Telefon numarası girerek bir kişinin bilgilerini değiştir
     *
     * @param int $grupID
     * @param int $numara
     * @param array $degisiklikler
     *
     * @return string
     * @throws AuthenticationException
     */
    public function numaraIleKisiDuzenle( $grupID, $numara, $degisiklikler ) {
        return $this->actions->editContactByNumber( $grupID, $numara, $degisiklikler );
    }

    /**
     * Kişi IDsi girerek kişi bilgilerini değiştir
     *
     * @param int $grupID
     * @param int $kisiID
     * @param array $degisiklikler
     *
     * @return string
     * @throws AuthenticationException
     */
    public function idIleKisiDuzenle( $grupID, $kisiID, $degisiklikler ) {
        return $this->actions->editContactById( $grupID, $kisiID, $degisiklikler );
    }
}


